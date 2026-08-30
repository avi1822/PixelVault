<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Package;
use App\Models\Computer;
use Yajra\DataTables\DataTables;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'date' => 'required|date|date_format:Y-m-d',
            'pc' => 'required|integer|exists:computers,cid',
            'packid' => 'required|integer|exists:packages,package_id',
            'start_time' => 'required|integer|min:0|max:23',
            'time' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $uid  = Auth::user()->id;
        $name = (Auth::user()->first_name) . " " . (Auth::user()->last_name);
        $date = $request->date;
        $pcid = $request->pc;
        $startTime = (int) $request->start_time;

        $package = Package::where('package_id', $request->packid)->first();
        $pkgTime = $package ? $package->package_time : 1;

        // Validate past dates
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot book reservations for past dates!'
            ], 422);
        }

        // Validate station operational status
        $station = Computer::where('cid', $pcid)->first();
        if ($station && in_array($station->status, ['MAINTENANCE', 'OFFLINE'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Station #' . $pcid . ' is currently under ' . $station->status . ' and cannot be reserved!'
            ], 422);
        }

        return DB::transaction(function () use ($uid, $name, $date, $pcid, $startTime, $package, $pkgTime, $request) {
            // Check for conflicting reservations for this station & date under transaction lock
            $existingReservations = Reservation::where('date', $date)
                ->where('computer_id', $pcid)
                ->lockForUpdate()
                ->get();

            foreach ($existingReservations as $res) {
                $resPkg = Package::where('package_id', $res->package_id)->first();
                $resDuration = $resPkg ? $resPkg->package_time : 1;
                $resStart = (int) $res->start_time;
                $resEnd = $resStart + $resDuration;

                $newEnd = $startTime + $pkgTime;

                // Check if time intervals overlap
                if (max($resStart, $startTime) < min($resEnd, $newEnd)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Station PC/PS5 #' . $pcid . ' is ALREADY BOOKED for this time slot (' . $res->time . ')! Please select a different time slot or station.'
                    ], 422);
                }
            }

            $reservation = new Reservation();

            $reservation->user_id = $uid;
            $reservation->user_name = $name;
            $reservation->date = $date;
            $reservation->time = $request->time;
            $reservation->computer_id = $pcid;
            $reservation->package_id = $request->packid;
            $reservation->start_time = $startTime;

            $reservation->save();

            return response()->json(['status' => 'ok']);
        });
    }

    public function anydata()
    {
        $reservations = Reservation::with(['user', 'package'])->select('reservations.*');

        return DataTables::of($reservations)
            ->addColumn('customer_name', function ($res) {
                if ($res->user) {
                    $fullName = trim($res->user->first_name . ' ' . $res->user->last_name);
                    $uName = $res->user->user_name ? (' (@' . $res->user->user_name . ')') : '';
                    return '<strong>' . ($fullName ?: $res->user->user_name) . $uName . '</strong><br><span style="font-size:0.8rem; color:#aaa;">📱 ' . ($res->user->phone_number ?? 'N/A') . '<br>✉️ ' . $res->user->email . '</span>';
                }
                return '<strong>' . ($res->user_name ?: 'Online Customer') . '</strong>';
            })
            ->addColumn('station_name', function ($res) {
                if ($res->computer_id == 3) {
                    return '✨ Upper Floor VIP PS5 #3';
                }
                return '🎮 Ground Floor PS5 #' . $res->computer_id;
            })
            ->addColumn('package_name', function ($res) {
                return $res->package ? $res->package->package_name : ('Package #' . $res->package_id);
            })
            ->rawColumns(['customer_name'])
            ->make(true);
    }
    public function userdata()
    {

        return DataTables::of(Reservation::query()->where("user_id", Auth::user()->id))->make(true);
    }

    public function respkgdata(Request $request)
    {
        $date = $request->date;
        $packid = $request->packid;
        $pcid = $request->pcid;

        $data["reservations"] = Reservation::select("start_time", "package_id")->orderBy("start_time")->where([["date", $date],["computer_id", $pcid]])->get();
        foreach ($data["reservations"] as $res) {
            $res["package_time"] = (Package::select("package_time")->where("package_id", $res["package_id"])->first())["package_time"];
        }

        $resdata["package"] =  Package::select("package_id", "package_name", "package_time", "package_price")->where("package_id", $packid)->get();

        if (count($data["reservations"]) != 0) {
            $pkgTime = $resdata["package"][0]->package_time;
            $rescounter = 0;
            $counter = 8;
            $resvlist = $data["reservations"];
            $resvlilen = count($data["reservations"]);
            $reshours = [];
            $availableTimes = [];

            foreach ($resvlist as $ri) {
                $st = $ri["start_time"];
                for ($rri = 0; $ri["package_time"] > $rri; $rri++) {
                    array_push($reshours, $st + $rri);
                }
            }
            $data["reshours"] = $reshours;

            while ($counter < 20) {
                $st = $resvlist[$rescounter]["start_time"];
                $pt = $resvlist[$rescounter]["package_time"];
                if ($counter != $st) {
                    $hash = false;
                    for ($rri = 0; $pkgTime > $rri; $rri++) {
                        if (in_array($counter + $rri, $reshours)) {
                            $hash = true;
                            break;
                        }
                    }
                    if (!$hash && $counter + $pkgTime <= 20) {
                        array_push($availableTimes, $counter);
                        $counter ++;//$counter += $pkgTime;
                    } else {
                        $counter++;
                    }
                } else {
                    if ($resvlilen - 1 > $rescounter) {
                        $rescounter++;
                    }
                    $counter += $pt;//$counter += $pt;
                }
            }
            $resdata["availableTimes"] = $availableTimes;
            $resdata["isFullDayAvailable"] = false;
        } else {
            $resdata["availableTimes"] = [];
            $resdata["isFullDayAvailable"] = true;
        }


        return $resdata;
    }
    public function viewPopuler(){
        $computers = Reservation::Select("computer_id", Reservation::raw('count(*) as total'))->groupBy("computer_id")->orderBy("total", "desc")->take(6)->get();

        return $computers;
    }
    public function getEventDetails(){
        $data["reservation"] = Reservation::orderBy('date')->latest()->select("id", "date","time", "computer_id")->where("user_id", Auth::user()->id)->take(10)->get();
        $data["regdate"] = Auth::user()->created_at;

        return $data;
    }
}

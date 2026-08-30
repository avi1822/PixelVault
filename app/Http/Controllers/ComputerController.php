<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Computer;
use App\Models\Game;
use App\Models\ComputerGame;
use Validator;
use Storage;
use DB;

class ComputerController extends Controller
{
    public function __construct(){
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "spec1"=>"required",
            "spec2"=>"required",
            "spec3"=>"required",
            "spec4"=>"required",
            "spec5"=>"required",
            "spec6"=>"required",
            "spec7"=>"required",
        ]);
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }

        $computer = new Computer();
        
        if(empty(Computer::count())){
            $cid = 1;
            $computer->cid = $cid;
        }else{
            $cid = Computer::latest()->first("cid")->cid + 1;
            $computer->cid = $cid;
        }
        $computer->spec1 = $request->spec1;
        $computer->spec2 = $request->spec2;
        $computer->spec3 = $request->spec3;
        $computer->spec4 = $request->spec4;
        $computer->spec5 = $request->spec5;
        $computer->spec6 = $request->spec6;
        $computer->spec7 = $request->spec7;
        
        

        $games = json_decode($request->games);
        $computer->save();

        foreach ($games as $game) {
            $computer->games()->attach($game);
        }
        
        return ['success' => true, 'cid' => $cid];
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            "spec1"=>"required",
            "spec2"=>"required",
            "spec3"=>"required",
            "spec4"=>"required",
            "spec5"=>"required",
            "spec6"=>"required",
            "spec7"=>"required",
        ]);
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }
        $cid = $request->cid;
        $computer = Computer::where('cid', $cid)->update(['spec1'=>$request->spec1, 'spec2'=>$request->spec2, 'spec3'=>$request->spec3, 'spec4'=>$request->spec4, 'spec5'=>$request->spec5, 'spec6'=>$request->spec6, 'spec7'=>$request->spec7,]);
        $computer = Computer::find($cid);
        $games = json_decode($request->games);
        $delgames = json_decode($request->delgames);

        foreach ($games as $game) {
            $computer->games()->attach($game);
        }
        foreach ($delgames as $game) {
            $computer->games()->detach($game);
        }

        return ['success' => true];
    }
    public function view(Request $request){
        $query = Computer::query();
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        return $query->get(["cid", "status"]);
    }
    public function viewAll(Request $request){
        return Computer::all("id", "cid", "spec1", "spec2", "spec3", "spec4", "spec5", "spec6", "spec7", "status");
    }
    public function viewhGame(Request $request){
        return Computer::all("id", "cid", "spec1", "spec2", "spec3", "spec4", "spec5", "spec6", "spec7", "status");
    }
    public function viewone(Request $request){
        $cid = $request->cid;
        $computer = Computer::with([
            'games' => function ($query1) {
                $query1->select(
                    "games.id",
                    "name"
                );
            }
        ])->select("cid", "spec1", "spec2", "spec3", "spec4", "spec5", "spec6", "spec7", "status")->where('cid',$cid)->get();
        return $computer;
    }

    public function viewoneimg(Request $request){
        $cid = $request->cid;
        $computer = Computer::with([
            'games' => function ($query1) {
                $query1->select(
                    "name",
                    "path"
                );
            }
        ])->select("cid", "spec1", "spec2", "spec3", "spec4", "spec5", "spec6", "spec7", "status")->where('cid',$cid)->get();
        if (isset($computer[0])) {
            foreach($computer[0]["games"] as $game){
                if (Storage::disk('local')->exists("gameimg/".$game->path)) {
                    $game["path"] = (Storage::disk('local')->get("gameimg/".$game->path));
                }
            }
        }
        return $computer;
    }

    public function updateStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'status' => 'required|in:AVAILABLE,RESERVED,PLAYING,MAINTENANCE,OFFLINE'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        $cid = $request->cid;
        $newStatus = $request->status;

        // Check if there are upcoming reservations for this station before setting to MAINTENANCE or OFFLINE
        if (in_array($newStatus, ['MAINTENANCE', 'OFFLINE'])) {
            $hasReservations = \App\Models\Reservation::where('computer_id', $cid)
                ->where('date', '>=', date('Y-m-d'))
                ->count();
            if ($hasReservations > 0 && !$request->has('confirm')) {
                return response()->json([
                    'success' => false,
                    'warning' => true,
                    'message' => "Station #" . $cid . " has " . $hasReservations . " upcoming reservation(s)! Setting it to " . $newStatus . " will block access. Pass 'confirm: true' to proceed."
                ]);
            }
        }

        Computer::where('cid', $cid)->update(['status' => $newStatus]);
        return response()->json(['success' => true, 'cid' => $cid, 'status' => $newStatus]);
    }

    public function delete(Request $request){
        $cid = $request->cid;

        // Prevent deleting if station has historical/upcoming reservations to protect relational integrity
        $reservationCount = \App\Models\Reservation::where('computer_id', $cid)->count();
        if ($reservationCount > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete station #' . $cid . ' because it has historical reservations. Please set status to OFFLINE or MAINTENANCE instead.'], 422);
        }

        Computer::where('cid', $cid)->delete();
        return response()->json(['success' => true, 'cid' => $cid]);
    }
}

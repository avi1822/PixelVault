<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorEntry;
use Yajra\DataTables\DataTables;
use Validator;
use DB;

class VisitorEntryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'visitor_name' => 'required',
            'hours_played' => 'required|numeric|min:1',
            'game_played'  => 'required',
            'zone_location'=> 'required',
            'entry_date'   => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }

        $entry = VisitorEntry::create([
            'visitor_name' => $request->visitor_name,
            'phone_number' => $request->phone_number ?? '',
            'hours_played' => (int) $request->hours_played,
            'game_played'  => $request->game_played,
            'food_item'    => $request->food_item ?? 'None',
            'zone_location'=> $request->zone_location,
            'entry_date'   => $request->entry_date,
        ]);

        return response()->json(['success' => true, 'data' => $entry]);
    }

    public function anydata(Request $request) {
        $query = VisitorEntry::query();

        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->where('entry_date', $request->filter_date);
        }
        if ($request->has('filter_month') && !empty($request->filter_month)) {
            $query->whereMonth('entry_date', date('m', strtotime($request->filter_month)))
                  ->whereYear('entry_date', date('Y', strtotime($request->filter_month)));
        }
        if ($request->has('filter_year') && !empty($request->filter_year)) {
            $query->whereYear('entry_date', $request->filter_year);
        }
        if ($request->has('filter_zone') && !empty($request->filter_zone)) {
            $query->where('zone_location', $request->filter_zone);
        }

        return DataTables::of($query->latest())->make(true);
    }

    public function analytics(Request $request) {
        $query = VisitorEntry::query();

        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->where('entry_date', $request->filter_date);
        }
        if ($request->has('filter_month') && !empty($request->filter_month)) {
            $query->whereMonth('entry_date', date('m', strtotime($request->filter_month)))
                  ->whereYear('entry_date', date('Y', strtotime($request->filter_month)));
        }
        if ($request->has('filter_year') && !empty($request->filter_year)) {
            $query->whereYear('entry_date', $request->filter_year);
        }

        $totalVisitors = (clone $query)->count();
        $totalHours = (clone $query)->sum('hours_played');
        $upperFloor = (clone $query)->where('zone_location', 'like', '%Upper Floor%')->count();
        $lowerFloor = (clone $query)->where('zone_location', 'like', '%Lower Floor%')->count();
        
        $topGame = (clone $query)->select('game_played', DB::raw('count(*) as count'))
                                 ->groupBy('game_played')
                                 ->orderByDesc('count')
                                 ->first();

        $topFood = (clone $query)->where('food_item', '!=', 'None')
                                 ->select('food_item', DB::raw('count(*) as count'))
                                 ->groupBy('food_item')
                                 ->orderByDesc('count')
                                 ->first();

        return response()->json([
            'totalVisitors' => $totalVisitors,
            'totalHours'    => $totalHours,
            'upperFloor'    => $upperFloor,
            'lowerFloor'    => $lowerFloor,
            'topGame'       => $topGame ? $topGame->game_played : 'N/A',
            'topFood'       => $topFood ? $topFood->food_item : 'N/A',
        ]);
    }

    public function delete(Request $request) {
        $id = $request->id;
        VisitorEntry::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}

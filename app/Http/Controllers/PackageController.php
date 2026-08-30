<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Validator;

class PackageController extends Controller
{
    public function __construct(){
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "packname"=>"required",
            "packtime"=>"required|numeric",
            "packprice"=>"required|numeric",
            "ground_floor_price"=>"nullable|numeric",
            "upper_floor_price"=>"nullable|numeric",
        ]);
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }
        $package = new Package();
        if(empty(Package::count())){
            $packid = 1;
            $package->package_id = $packid;
        }else{
            $packid = Package::latest()->first("package_id")->package_id + 1;
            $package->package_id = $packid;
        }
        $packname = $request->packname;
        $packtime = $request->packtime;
        $packprice = $request->packprice;
        $groundPrice = $request->ground_floor_price ?? ($packtime * 99);
        $upperPrice = $request->upper_floor_price ?? ($packtime * 120);

        $package->package_name = $packname;
        $package->package_time = $packtime;
        $package->package_price = $packprice;
        $package->ground_floor_price = $groundPrice;
        $package->upper_floor_price = $upperPrice;
        
        $package->save();

        return response()->json([
            'success' => true, 
            "package_id"=>$packid, 
            'package_name' => $packname, 
            "package_time"=> $packtime, 
            "package_price"=>$packprice,
            "ground_floor_price"=>$groundPrice,
            "upper_floor_price"=>$upperPrice
        ]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            "packname"=>"required",
            "packtime"=>"required|numeric",
            "packprice"=>"required|numeric",
            "ground_floor_price"=>"nullable|numeric",
            "upper_floor_price"=>"nullable|numeric",
        ]);
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }
        $packid = $request->packid;
        $packname = $request->packname;
        $packtime = $request->packtime;
        $packprice = $request->packprice;
        $groundPrice = $request->ground_floor_price ?? ($packtime * 99);
        $upperPrice = $request->upper_floor_price ?? ($packtime * 120);

        Package::where('package_id', $packid)->update([
            'package_name'=>$packname, 
            'package_time'=>$packtime, 
            'package_price'=>$packprice,
            'ground_floor_price'=>$groundPrice,
            'upper_floor_price'=>$upperPrice
        ]);

        return ['success' => true];
    }

    public function delete(Request $request){
        $packid = $request->packid;
        Package::where('package_id', $packid)->delete();

        return response()->json(['package_id' => $packid]);
    }

    public function viewAll(Request $request){
        $package = Package::orderBy("package_time")
            ->select("package_id","package_name", "package_time", "package_price", "ground_floor_price", "upper_floor_price")
            ->get();

        return $package;
    }

    public function viewone(Request $request){
        $packid = $request->packid;
        $package = Package::select("package_id","package_name", "package_time", "package_price", "ground_floor_price", "upper_floor_price")
            ->where("package_id",$packid)
            ->get();

        return $package;
    }
}

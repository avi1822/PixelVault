<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Game;
use Storage;
use DB;
use Validator;

class GameController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "name"=>"required",
            "imagelink"=>"required",
        ]);
        if ($validator->fails()) {
            return ["success" => false, "message" => $validator->errors()];
        }
        
        $game = new Game();

        $name = $request->name;
        $url = $request->imagelink;

        if(empty(Game::count())){
            $path = "1.rpath";
        }else{
            $path = (Game::latest()->first("id")->id + 1).".rpath";
        }

        $game->name = $name;
        $game->path = $path;

        Storage::put("gameimg/".$path, $url);
        
        $game->save();

        return ["success" => true];
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            "gameid"=>"required",
            "name"=>"required",
            "imagelink"=>"required",
        ]);
        if ($validator->fails()) {
            return ["success" => false, "message" => $validator->errors()];
        }
        $gameid = $request->gameid;
        $name = $request->name;
        $url = $request->imagelink;
        $path = $gameid.".rpath";

        Storage::put("gameimg/".$path, $url);

        $game = Game::where('id', $gameid)->update(['name'=>$name, 'path'=>$path]);
        
        return ["success" => true];
    }
      
    public function viewone(Request $request){
        $id = $request->id;
        $game = Game::select("path")-> where("id",$id)->get();
        if (isset($game[0]) && Storage::disk('local')->exists("gameimg/".$game[0]->path)) {
            $game[0]["path"] = (Storage::disk('local')->get("gameimg/".$game[0]->path));
        }

        return $game;
    }
    public function view(Request $request){
        $games = Game::all("id", "name");

        return $games;

    }
    public function viewhimg(Request $request){
        $games = Game::all("id", "name", "path");
        foreach($games as $game){
            if (Storage::disk('local')->exists("gameimg/".$game->path)) {
                $game["path"] = (Storage::disk('local')->get("gameimg/".$game->path));
            }
        }

        return $games;

    }
    public function delete(Request $request){
        $id = $request->id;
        Game::where('id', $id)->delete();

        return response()->json(['id' => $id]);
    }

    public function anydata(){

        return Datatables::of(Game::query())->make(true);

    }
    public function partofdata(){
        $games = Game::select("id", "name", "path")->Paginate(18);

        $updatedItems = $games->getCollection();
        foreach($updatedItems as $game){
            if (Storage::disk('local')->exists("gameimg/".$game->path)) {
                $game["path"] = (Storage::disk('local')->get("gameimg/".$game->path));
            }
        }
        $games->setCollection($updatedItems);

        return $games;

    }
    public function getLatest(){
        $games = Game::latest()->take(6)->select("id", "name", "path")->get();
        foreach($games as $game){
            if (Storage::disk('local')->exists("gameimg/".$game->path)) {
                $game["path"] = (Storage::disk('local')->get("gameimg/".$game->path));
            }
        }
        return $games;
    }
}

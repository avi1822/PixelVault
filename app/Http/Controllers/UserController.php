<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\Reservation;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "firstname"=>"required",
            "lastname"=>"required",
            "username"=>"required",
            "phonenumber"=>"required|min:10|max:15",
            "address"=>"required",
            "email"=>"required|email",
            "password"=>"required|confirmed|min:6",
            "password_confirmation"=>"required|min:6",
        ]);
        $user = new User();

        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->user_name = $request->username;
        $user->phone_number = $request->phonenumber;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->propic = rand(1,21);

        $user->save();

        return redirect('login');
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            "firstname"=>"required",
            "lastname"=>"required",
            "username"=>"required",
            "phonenumber"=>"required|min:10|max:15",
            "address"=>"required",
            "email"=>"required|email",
            "propic"=>"required",
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }

        $first_name = $request->firstname;
        $last_name = $request->lastname;
        $user_name = $request->username;
        $phone_number = $request->phonenumber;
        $address = $request->address;
        $email = $request->email;
        $propic = $request->propic;

        $user = User::where("id", Auth::user()->id)->update(["first_name"=>$first_name,"last_name"=>$last_name,"user_name"=>$user_name,"phone_number"=>$phone_number,"address"=>$address,"email"=>$email, "propic"=>$propic]);


        return ['success' => true];
    }

    public function updatep(Request $request){
        $validator = Validator::make($request->all(),[
            "oldpassword"=>['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            "newpassword"=>"required|confirmed|min:6",  
            "newpassword_confirmation"=>"required|confirmed|min:6",  
        ]);
        if($validator->fails()){
            return ['success' => false, 'message' => $validator->errors()];
        }
        $user = User::where("id", Auth::user()->id)->update(["password"=>Hash::make($request->newpassword)]);

        return ["success" => true];
    }
    public function dologin(Request $request) {
        $request->validate([
            "username"=>"required",
            "password"=>"required"
        ]);

        $uname = $request->username;
        $password = $request->password;
        if (Auth::attempt(['user_name' => $uname, 'password' => $password])) {
            if(Auth::user()->isadmin){
                return redirect('/admin');
            }else{
                return redirect('/user');
            }
        }else{
            return back()->withErrors(["username"=>"The username or password incorrect!", "password"=>"The username or password incorrect!"]);
        }
     }
     public function viewOne(){
        $user = User::select("first_name","last_name","user_name","phone_number","address","email","propic")-> where("id",Auth::user()->id)->get();
        $res = Reservation::where('user_id', Auth::user()->id)->count();
        $userType = "";
        switch ($res) {
            case $res < 100:
                $userType = "Silver Member";
                break;
            case $res < 200:
                $userType = "Gold Member";
                $res =$res - 100;
                break;
            case $res < 300:
                $userType = "Platinum Member";
                $res =$res - 200;
                break;
            case $res < 400:
                $userType = "Diamond Member";
                $res =$res - 300;
                break;
            default:
                $userType = "Silver Member";
                break;
        }
        $user["reservations"] = $res;
        $user["userType"] = $userType;
        return $user;
     }
     public function anydata(){

        return Datatables::of(User::query())->make(true);

    }
}

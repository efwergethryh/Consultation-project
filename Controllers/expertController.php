<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Photo;
use App\Models\expert;
use App\Models\appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Psy\Exception\Exception;
use App\Models\AvailableTimes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use \Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class expertController extends Controller
{   public function getexpertprofile(Request $q){
    $expert= PersonalAccessToken::findToken($q->bearerToken())->tokenable;
     /*  Auth::user();expert::where('id','LIKE','%' . $id .'%');*/
    return response()->json([
        'msg'=>$expert->only(["name","email","phone","id"])
    ]);
}
    public function addDetails(Request $request){
        $expert =new expert;
        $id = PersonalAccessToken::findToken($request->bearerToken())->tokenable->id;
        $valid =$request->validate([

            'AvailableTime'=>'date:Y-m-d H:i:s',
            'Address'=>'string'
        ]);
       
        $available_Time = AvailableTimes::create([
            'AvailableTime'=>$request->AvailableTime,
            'expert_id'=>$id,
            'status'=>true
        ]);
        
        $available_Time->save();
        expert::insert(['Address'=>$request->Address]);
        $expert->save();
        $response = ['message','details have been added successfully',$request->AvailableTime,
                    $request->Address];
        return response()->json($response, 200);
    }


    public function create_expert(Request $req){
        $validator = $req->validate([
            'name'=>'required',
            'email'=>'required|string|unique:experts',
            'password'=>'required',
            'phone'=>'required'
         ]);
                    $expert = expert::create(
                [
                    'name'=>$req->name,
                    'email'=>$req->email,
                    'password'=>Hash::make($req->password),
                    'phone'=>$req->phone,
                    'api_token'=>Str::random(60)
                ]
             );
             $expert->save();
             return response()->json([
                'message'=>'expert registered successfully'
             ]);
         
         
    }
   
    public function loginExpert(Request $req){
        $getexpert =expert::where('email','LIKE','%' . $req->email . '%')->get();
        if(!isset($getexpert)){
            return \response()->json([
                'message'=>'email is invalid :('
            ]);
        }
        /*DB::table('experts')->where('email', $req->email)->first()->email;*/
        $email =$getexpert->pluck("email");
        $id = $getexpert->pluck("id");
        $password =$getexpert->pluck("password");
        $expert = expert::where('id',$id)->first();
            $cred = $req->only('email','password');
            
            if(password_verify($req->password,$password)){
                return \response()->json([
                    'message'=>'login failed!',$password,$req->password
                    
                ]);
            } 
            $plainTextToken = $expert->createToken('expert_token')->plainTextToken;
        return response()->json([
            'message'=>'logged in successfully!',
            'token'=>$plainTextToken,$id
        ]);

        }
    public function getAllAppointments(Request $req){
        $id = PersonalAccessToken::findToken($req->bearerToken())->tokenable->id;
        $getapppointments = appointment::where('expert_id','LIKE','%' . $id .'%')->get();
        if(!isset($getapppointments)){
            return response()->json(['msg'=>'expert id is not found!']);
        }
        return response()->json([
            'user='=>$id,
            'appointments:'=>$getapppointments->pluck(["Appointment"])->all()
             ]);
         }
         
}

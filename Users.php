<?php

namespace App\Http\Controllers;

use session;

use Permissions;
use App\Models\User;
use App\Models\Photo;
use App\Models\expert;
use App\Models\appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AvailableTimes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use \Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{ 
    
    public function getUser(){
    $user = auth()->user();
    return response()->json([
        'user details:'=>$user
    ]);
}
    public function Book_appointment(Request $req){
        

        $validator = $req->validate([
            'expert_email'=>'required',
            'appointment'=>'date:Y-m-d ',
            'appointment_id'=>'required|integer'
                       
        ]);
        $expert_id =expert::where('id','LIKE','%' . $req->expert_email . '%')->get()->pluck(["id"]);
        $avai = AvailableTimes::where('id','LIKE','%' . $req->appointment_id . '%')
       ->where('expert_id','LIKE','%' . $expert_id . '%')->get();
       if(!isset($avai)){
        return response()->json(['msg'=>'expert id is not found :|']);
       }
        $availabletime= $avai->AvailableTime;
        $status = $avai->status; 
        if($status == false){
            return response()->json([
                'message'=>'appointment is already booked :('
            ]);
        }
        if(strcmp($req->appointment,$availabletime)){
            
                $appointment = appointment::create([
                    'appointment'=>$req->appointment,
                    'expert_id'=>$expert_id
                    
                ]); 
                $appointment->save();
                AvailableTimes::where('id','=', $req->appointment_id)
                ->where('expert_id','=', $expert_id )->update(['status'=>false]);
                expert::where('id','LIKE','%' . $expert_id .'%')->update(['AppointmentId'=>$req->appointment_id]);
                return response()->json([
                    'message'=>'appointment has been booked successfully :)',
                    'appointment'=>$availabletime,
                    'appointmet id'=>$req->appointment_id
                ]);
    }
           
    }
    
    
    public function retreive(){
     return User::all();
}  
    public function Register(Request $req){
       
     $validator = $req->validate([
        'name'=>'required',
        'email'=>'required|string|unique:users',
        'password'=>'required',
        'phone'=>'nullable',
        

     ]);
     
     
        $user = User::create(
            [
                'name'=>$req->name,
                'email'=>$req->email,
                'password'=>Hash::make($req->password),
                'phone'=>$req->phone,
                'api_token' => Str::random(60),
            ]
         );
         $user->save();
         return response()->json([
            'message'=>'User registered successfully'
         ]);
     
     
    }
    
    public function login(Request $req){
        $getuser = User::where('email','LIKE','%' . $req->email .'%')->get();
        $email =$getuser->pluck("email");
        $id = $getuser->pluck("id");
        $password =$getuser->pluck("password");
        $user = User::where('id',$id)->first();
            $cred = $req->only('email','password');
            
            if(password_verify($req->password,$password)){
                return \response()->json([
                    'message'=>'login failed!',$password,$req->password
                    
                ]);
            } 
            $plainTextToken = $user->createToken('user-token')->plainTextToken;
        return response()->json([
            'message'=>'logged in successfully!',
            'token'=>$plainTextToken,$id
        ]);

    }
    public function RetrieveUserInfo(Request $request){
        $user = PersonalAccessToken::findToken($request->bearerToken())->first()->tokenable;
        return response()->json([
            'user'=>$user->only(["name","email","phone","id"])
        ]);

    }
    public function RetreiveAllexperts(Request $req){
       $experts_emails = DB::table('experts')->get()->pluck(["email"])->all();
       $experts_names = DB::table('experts')->get()->pluck(["name"])->all();
       $experts_IDs = DB::table('experts')->get()->pluck(["id"])->all();
       return response()->json([
        'experts emails:'=>$experts_emails,
        'experts names:'=>$experts_names,
        'experts IDs:'=>$experts_IDs]);
    }
    public function retreiveExpert(Request $req){
        $expert = expert::where('id','LIKE','%' . $req->id . '%')->first();
        $photo = Photo::where('expert_id','LIKE','%' . $req->id . '%')->first()->Photo_path;
        return response()->json([
            'expert info'=>$expert,
            'expert photo'=>$photo
        ]);
    }                                          
  
    
}

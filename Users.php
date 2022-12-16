<?php

namespace App\Http\Controllers;

use session;

use Permissions;
use App\Models\User;
use App\Models\expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Santcum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{   public function retreive(){
     return User::all();
}  
    public function Register(Request $req){
       
     $validator = $req->validate([
        'name'=>'required',
        'email'=>'required|string|unique:users',
        'password'=>'required',
        'phone'=>'required'
     ]);
     
     
        $user = User::create(
            [
                'name'=>$req->name,
                'email'=>$req->email,
                'password'=>Hash::make($req->password),
                'role_id'=>2,
                'phone'=>$req->phone
            ]
         );
         $user->save();
         return response()->json([
            'message'=>'User registered successfully'
         ]);
     
     
    }
    
    public function login(Request $req){
        
      $email = DB::table('users')->where('email',$req->email)->first()->email;
        $password = DB::table('users')->where('email',$req->email)->first()->password;
        
        if(!strcmp($email,$req->email) && !strcmp($password,$req->password)){
            
            return \response()->json([
                'message'=>'login failed!'    
            ]);

        } 
        
       return response()->json([
        'message'=>'logged in successfully!',
       
        
        
       ]);


    }
    public function deleteUser(User $user){
        DB::table('users')->where('id',$user->id)->delete();
        return response()->json(['message'=>'user deleted successfully']);
    }
    public function logout(){
        Auth::logout();
        return response()->json([
            'message'=>'logged out successfully'
        ]);
    }
}

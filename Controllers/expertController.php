<?php

namespace App\Http\Controllers;

use App\Models\expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Santcum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;


class expertController extends Controller
{
    public function create_expert(Request $req){
        $validator = $req->validate([
            'name'=>'nullable',
            'email'=>'required|string|unique:users',
            'password'=>'required',
            'phone'=>'nullable'
         ]);
         
         
            $expert = expert::create(
                [
                    'name'=>$req->name,
                    'email'=>$req->email,
                    'password'=>Hash::make($req->password),
                    'role_id'=>1,
                    'phone'=>$req->phone
                ]
             );
             $expert->save();
             return response()->json([
                'message'=>'expert registered successfully'
             ]);
         
         
    }
    public function loginExpert(Request $req){
    $email = expert::where('email','LIKE','%' . $req->email . '%')->get();
    //    ->orWhere('id','LIKE','%' . $req->id . '%')
    //    ->first()->email;
       $password = expert::where('email','LIKE','%' . $req->email . '%')->get();
      // $id = expert::where('email','LIKE','%' . $req->email . '%')->get();
       //$expert = expert::where('id','LIKE','%' . $id . '%' )->get();
        $cred = $req->only('email','password');
        if(!strcmp($email,$req->email) && !strcmp($password,$req->password)){
          
            return \response()->json([
                'message'=>'login failed!',$password
                
            ]);
          // return redirect->route('login');
        } 
       
       return response()->json([
        'message'=>'logged in successfully!',$email
       ]);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Verot\Upload\Upload;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class PhotoController extends Controller
{
    public function uploadPhoto(Request $request){
        $id = PersonalAccessToken::findToken($request->bearerToken())->tokenable->id;
        $request->validate([
            'Photo_path'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
        $image = new Upload($_FILES['Photo_path']);
        if($request->hasFile('Photo_path')){
            $file = $request->file('Photo_path');
            $extension = $file->getClientOriginalExtension();
            $filename=time().'.'.$extension;  
            
            $image_path = $request->file('Photo_path')->store('Photo_path', 'public');
            $image->image = $filename;
        }
        else  
        {  
            return response()->json(['message'=>'photo could not uploaded :(']);  
            $image->image='';  
        }  
        $photo= Photo::create([
            'Photo_path'=>$request->Photo_path,
            'expert_id'=>$id
        ]);
        $photo->save();
        return response()->json([
            'msg'=>'Photo has been uploaded successfully'
        ]);
    }
}

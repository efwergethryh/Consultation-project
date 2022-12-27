<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::get('retreive/{id}','Users@retreive');
Route::post('createRole','RoleController@create_role');
Route::post('User','Users@Register');
Route::post('loginexpert','expertController@loginExpert');
Route::post('expert','expertController@create_expert');
Route::middleware(['expert'])->group(function () {
    Route::post('adddetails','expertController@addDetails');
    Route::get('getProfile','expertController@getexpertprofile');
    Route::get('getAppointment','expertController@getAllAppointments');
    Route::post('uploadPhoto','PhotoController@uploadPhoto');
    
});
Route::get('getUser','Users@getUser');
Route::middleware(['middleware'=>'user'])->group(function(){
    Route::post('Book_appointment','Users@Book_appointment');
    Route::get('UserInfo','Users@RetrieveUserInfo');
   // Route::get('Allexperts','Users@RetreiveAllexperts');
    Route::post('getExpert','Users@retreiveExpert');
    Route::get('getAllexperts','Users@RetreiveAllexperts');
});
Route::post('login','Users@login');
// Route::get('getAppointments','expertController@getAllAppointments');
//Route::post('CreateRole','RoleController@create_role');
Route::get('logout','Users@logout');
Route::delete('DeleteUserByID/{id}','Users@deleteUser');
// Route::prefix('')->group()
// Route::prefix('Expert')->middleware('can:')->group(){
//     Route::get('Surf_consultation','ExpertController@Surf_consultation');
// }
Route::middleware('auth:sanctum')->get('/user',function (Request $request) {
    return $request->user();
});

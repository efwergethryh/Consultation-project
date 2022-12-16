<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('retreive/{id}','Users@retreive');
Route::post('createRole','RoleController@create_role');
Route::post('User','Users@Register');
Route::post('loginexpert','expertController@loginExpert');
Route::post('expert','expertController@create_expert');
Route::post('addDetails','expertController@Add_details');
Route::post('login','Users@login');
Route::post('CreateRole','RoleController@create_role');
Route::get('logout','Users@logout');
Route::delete('DeleteUserByID/{id}','Users@deleteUser');
// Route::prefix('')->group()
// Route::prefix('Expert')->middleware('can:')->group(){
//     Route::get('Surf_consultation','ExpertController@Surf_consultation');
// }
Route::middleware('auth:sanctum')->get('/user',function (Request $request) {
    return $request->user();
});

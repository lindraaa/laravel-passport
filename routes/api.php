<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix'=>'v1',
              'middleware' => ['cors']], function () {
                Route::post("/register",[ApiAuthController::class,'register']);
                Route::post("/login",[ApiAuthController::class,'login']);
            });

Route::group(['prefix'=>'auth/v1',
              'middleware'=>['auth:api']], function(){
                Route::get('/',function(){
                        return User::all();
                    });
                    Route::post("/logout",[ApiAuthController::class,'logout']);

              });
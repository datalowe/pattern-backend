<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Adm; // Admin class
use App\Models\Customer; // Customer class
use App\Models\Scooter; // Scooter class
use App\Models\Station; // Station class
use App\Models\City; // City class
use App\Models\Logg; // Logg class

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

Route::get('scooters', function() {
    // TODO: This (and other routes) should be wrapped
    // in middleware.
    return Scooter::all();
});

////////// ADMIN //////////
Route::put('/admin/{id}',
    'App\Http\Controllers\Sctr\AdmController@updateAdmin'
);
///////////////////////////


////////// USERS //////////
Route::get('/users', function () {
    return Customer::all();
});

Route::get('/users/{id}', function ($id) {
    return Customer::where('id', $id)->get();
});

Route::post('/users',
    'App\Http\Controllers\Sctr\CustomerController@addCustomer'
);

Route::put('/users/{id}',
    'App\Http\Controllers\Sctr\CustomerController@updateCustomer'
);
///////////////////////////


////////// SCOOTERS //////////
Route::get('/scooters', function () {
    return Scooter::all();
});

Route::get('/scooters/{id}', function ($id) {
    return Scooter::where('id', $id)->get();
});

Route::put('/scooters/{id}',
    'App\Http\Controllers\Sctr\ScooterController@updateScooter'
);
//////////////////////////////


////////// STATIONS //////////
Route::get('/stations', function () {
    return Station::all();
});

Route::get('/station/{id}', function ($id) {
    return Station::where('id', $id)->get();
});

Route::put('/stations/{id}',
    'App\Http\Controllers\Sctr\StationController@updateStation'
);
//////////////////////////////


////////// CITIES //////////
Route::get('/cities', function () {
    return City::all();
});

Route::get('/cities/{id}', function ($id) {
    return City::where('id', $id)->get();
});

Route::put('/cities/{id}',
    'App\Http\Controllers\Sctr\CityController@updateCity'
);
////////////////////////////


////////// LOGGAR //////////
Route::get('/logs', function () {
    return Logg::all();
});

Route::get('/logs/{id}', function ($id) {
    return Logg::where('id', $id)->get();
});

Route::put('/logs/{id}',
    'App\Http\Controllers\Sctr\LoggController@updateLogg'
);
////////////////////////////

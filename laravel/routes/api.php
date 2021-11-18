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

Route::get('/cities/{id}/scooters', function ($id) {
    return Scooter::where('city_id', $id)->get();
});

Route::get('/cities/{id}/stations', function ($id) {
    return Station::where('city_id', $id)->get();
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

////////// CUSTOMER (user) OAUTH //////////

// route which returns a github login url
Route::get('/auth/github/redirect', function () {
    // return Socialite::driver('github')->stateless()->redirect();

    $redirResp = Socialite::driver('github')->stateless()->redirect();

    // return github login url, including 'redirect_uri' query parameter
    return response()->json(
        ['login_url' => $redirResp->getTargetUrl()]
    );

    // ALTERNATIVE METHOD BELOW, returning target (GitHub) url without the 'redirect_uri'
    // query parameter

    // Remove redirect_uri parameter from target url, since the frontend should
    // specify the redirect_uri param.
    // $key = 'redirect_uri';

    // $returnUrl = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $redirResp->getTargetUrl());

    // parameter
    // return response()->json(
    //     ['login_url' => $redirResp->getTargetUrl()]
    // );
});

// this endpoint is to be hit by frontend with query parameter 'code', (which it
// in turn has received from GitHub after user successfully logged in)
Route::get('/auth/github/callback', function () {
    $user = Socialite::driver('github')->stateless()->user();
    // TODO: store user token in database (once it's been decided how
    // - have a special 'oauth_token' column in user table?)

    // the OAuth token is attached in a cookie, which should be sent with
    // every following AJAX request from frontend. Note that the third
    // argument of the cookie method says when the cookie is to expire
    // in _minutes_ while the $user->expiresIn value is in _seconds_,
    // hence we divide by 60.
    return response()->json(
        ['authentication_outcome' => "success"]
    )->cookie('oauth_token', $user->token, $user->expiresIn / 60);
});
////////////////////////////

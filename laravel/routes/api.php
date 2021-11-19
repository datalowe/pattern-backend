<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Adm; // Admin class
use App\Models\Customer; // Customer class
use App\Models\Scooter; // Scooter class
use App\Models\Station; // Station class
use App\Models\City; // City class
use App\Models\Logg; // Logg class

// OAuth
use Laravel\Socialite\Facades\Socialite;

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

// must be placed before /scooters, otherwise empty array
Route::get('/scooters/available', function () {
    return DB::table('scooter')
        ->leftJoin('station', 'scooter.station_id', '=', 'station.id')
            ->where('station.type', '!=', 'charge') // exclude charge stations
            ->where('scooter.customer_id', '=', null) // no current customer
            ->where('scooter.battery_level', '>=', 10) // minimum battery level
            ->where('scooter.status', '=', 'active')
            ->orderBy('scooter.id', 'asc')
            ->get('scooter.*');
    });


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

Route::get('/stations/{id}', function ($id) {
    return Station::where('id', $id)->get();
});

Route::get('/stations/{id}/scooters', function ($id) {
    return Scooter::where('station_id', $id)->get();
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

// route which returns a github login url in a JSON response, which frontend
// should make user go to in a separate tab.
Route::get('/auth/github/redirect', function () {
    // need to use 'stateless()' method here since API routes don't involve
    // session middleware, and Socialite by default relies on Session. calling
    // 'stateless()' disables this reliance.
    $redirResp = Socialite::driver('github')->stateless()->redirect();

    // return github login url, including 'redirect_uri' query parameter
    return response()->json(
        ['login_url' => $redirResp->getTargetUrl()]
    );
});

// the user should be sent to this 'callback' route endpoint by github.
Route::get('/auth/github/callback', function () {
    // again, need to use 'stateless()' (see comment in 'redirect' route code)
    $user = Socialite::driver('github')->stateless()->user();
    // TODO: store user token in database, once it's been decided how
    // - have a special 'oauth_token' column in user table. here, it should
    // first be checked if the user already exists, (using $user->getNickName() value)
    // to ensure that no 'duplicate' user records are created (should also be
    // controlled on database level by having github_username column set to
    // UNIQUE). moreover, this check
    // should be done first against the 'adm' table to see if the user is
    // actually an admin, then against the (regular) 'user' table. if
    // the user doesn't exist, a new 'user' record is created.

    // the OAuth token is attached in a cookie, which should be sent with
    // every following AJAX request from frontend. Note that the third
    // argument of the cookie method says when the cookie is to expire
    // in _minutes_ while the $user->expiresIn value is in _seconds_,
    // hence we divide by 60.
    return response(
        'Hej ' . $user->getNickName() . '! Du är nu inloggad via GitHub. Vänligen stäng den här fliken och återvänd till SCTR.'
    )->cookie('oauth_token', $user->token, $user->expiresIn / 60);
});

// route for simply checking if the user is logged in via OAuth
Route::get('auth/github/check-usertype', function (Request $req) {
    $token = $req->cookie('oauth_token');
    // TODO: add check against database tables, adm and user. make
    // add 'user_type': 'admin' response branch accordingly.
    if ($token) {
        return response()->json(['user_type' => 'user']);
    } else {
        return response()->json(['user_type' => 'not_logged_in']);
    }
});

////////////////////////////

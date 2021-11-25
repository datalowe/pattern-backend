<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Customer; // Customer class
use App\Models\Scooter; // Scooter class
use App\Models\Station; // Station class
use App\Models\City; // City class
use App\Models\Logg; // Logg class

use App\Http\Controllers\Sctr\OAuthController;

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

Route::get('/users/{id}/logs', function ($id) {
    return DB::table('customer')
    ->join('logg', function ($join) use(&$id) { // 'use' accesses outer scope variable
        $join->on('customer.id', '=', 'logg.customer_id')
            ->select('customer.*', 'logg.*')
            ->where('customer.id', $id);
    })
    ->get();
});

Route::post('/users',
    'App\Http\Controllers\Sctr\CustomerController@addCustomer'
);

Route::put('/users/{id}',
    'App\Http\Controllers\Sctr\CustomerController@updateCustomer'
);
///////////////////////////


////////// SCOOTERS //////////
Route::get('/scooters', function (Request $req) {
    // NOTE NEW! Filtering based on who requested the data (customers
    // only get active scooters) TODO ensure that correct filtering
    // is applied for customers (more criteria needed? might be appropriate
    // to create a custom method on Scooter class if filtering becomes complex)
    if (Customer::isCustomerReq($req)) {
        return Scooter::where('status', 'active')->get();
    }
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

////////// CUSTOMER OAUTH //////////

// route which returns a github login url in a JSON response, which frontend
// should make user go to in a separate tab.
Route::get('/auth/github/redirect', [OAuthController::class, 'githubRedirectCustomer']);

// the user should be sent to this 'callback' route endpoint by github.
Route::get('/auth/github/callback', [OAuthController::class, 'githubCallbackCustomer']);

////////////////////////////

////////// ADMIN OAUTH //////////

Route::get('/auth/github/redirect/admin', [OAuthController::class, 'githubRedirectAdmin']);

Route::get('/auth/github/callback/admin', [OAuthController::class, 'githubCallbackAdmin']);

////////////////////////////



// route for checking if user is logged in with OAuth, and if so, as admin or customer
Route::get('auth/github/check-usertype', [OAuthController::class, 'checkUserType']);

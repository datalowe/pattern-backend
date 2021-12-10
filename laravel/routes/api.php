<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Sctr\CityController;
use App\Http\Controllers\Sctr\CustomerController;
use App\Http\Controllers\Sctr\LoggController;
use App\Http\Controllers\Sctr\OAuthController;
use App\Http\Controllers\Sctr\ScooterController;
use App\Http\Controllers\Sctr\StationController;

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

Route::middleware(['has.valid.token'])->group(function(){
    ////////// USERS //////////
    Route::get('/users', [CustomerController::class, 'getAllCustomers'])->middleware('admin.or.associate');

    Route::get('/users/{id}', [CustomerController::class, 'getSingleCustomer'])->middleware('admin.or.owner');

    Route::get('/users/{id}/logs', [CustomerController::class, 'getCustomerWithLoggs'])->middleware('admin.or.owner');

    Route::put('/users/{id}', [CustomerController::class, 'updateCustomer'])->middleware('admin.or.owner');
    ///////////////////////////


    ////////// SCOOTERS //////////
    Route::get('/scooters', [ScooterController::class, 'getAllScooters']);

    Route::get('/scooters/{id}', [ScooterController::class, 'getSingleScooter']);

    Route::put('/scooters/{id}', [ScooterController::class, 'updateScooter']);
    
    ////////// STATIONS //////////
    Route::get('/stations', [StationController::class, 'getAllStations']);

    Route::get('/stations/{id}', [StationController::class, 'getSingleStation']);

    Route::get('/stations/{id}/scooters', [StationController::class, 'getLinkedScooters']);

    //////////////////////////////


    ////////// CITIES //////////
    Route::get('/cities', [CityController::class, 'getAllCities']);

    Route::get('/cities/{id}', [CityController::class, 'getSingleCity']);

    Route::get('/cities/{id}/scooters', [CityController::class, 'getLinkedScooters']);

    Route::get('/cities/{id}/stations', [CityController::class, 'getLinkedStations']);

    Route::put('/cities/{id}', [CityController::class, 'updateCity'])->middleware('admin');
    ////////////////////////////

    ////////// LOGGAR //////////

    Route::get('/logs', [LoggController::class, 'getAllLogs'])->middleware('admin');

    ////////////////////////////
});



// routes specifically for use by scooter client/simulator //
Route::get('/scooter-client/scooters/', [ScooterController::class, 'getAllScooters']);
Route::get('/scooter-client/scooters/{id}', [ScooterController::class, 'getSingleScooter']);

Route::put('/scooter-client/scooters/{id}', [ScooterController::class, 'updateScooterCache']);

Route::post('/scooter-client/scooters/flush-cache', [ScooterController::class, 'syncCacheWithDatabase']);
//////////////////////////////


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

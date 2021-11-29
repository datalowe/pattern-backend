<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Customer; // model of table
use Illuminate\Http\Request; // for POST route

class CustomerController extends Controller
{
    public function addCustomer(Request $body) // $body contains key-value from POST
    {
        $user = new Customer;

        // add column in row if body contains value
        isset($body->username) ? $user->username = $body->username : null;
        isset($body->token) ? $user->token = $body->token : null;
        
        // create new row
        $user->save();
    }

    // $id from web.php contains city_id, $body contains key-value from POST
    public function updateCustomer($id, Request $body)
    {
        // find scooter by its primary key
        $user = Customer::find($id);

        // update specific column in row if body contains key-value
        isset($body->username) ? $user->username = $body->username : null; // assign $body->location
        isset($body->token) ? $user->token = $body->token : null;
        isset($body->funds) ? $user->funds = $body->funds : null;
        isset($body->payment_terms) ? $user->payment_terms = $body->payment_terms : null;

        // update user
        $user->save();
    }
}

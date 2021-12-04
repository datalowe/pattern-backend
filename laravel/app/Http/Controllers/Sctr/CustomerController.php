<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Customer; // model of table
use Illuminate\Http\Request;

// for POST route

class CustomerController extends Controller
{
    public function addCustomer(Request $body) // $body contains key-value from POST
    {
        $user = new Customer();

        // add column in row if body contains value
        isset($body->username) ? $user->username = $body->username : null;
        isset($body->token) ? $user->token = $body->token : null;

        // create new row
        $user->save();
    }

    // $id from web.php contains city_id, $body contains key-value from POST
    public function updateCustomer($idNr, Request $body)
    {
        // find Customer by its primary key
        $user = Customer::find($idNr);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $user->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $user->$column = $value;
        }

        // update user
        $user->save();
    }
}

<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Customer; // model of table
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

// for POST route

class CustomerController extends Controller
{
    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function getSingleCustomer($idNr)
    {
        return Customer::where('id', $idNr)->get();
    }

    public function getCustomerWithLoggs($idNr)
    {
        return DB::table('customer')
        ->where('customer.id', $idNr)
        ->join('logg', 'customer.id', '=', 'logg.customer_id')
        ->get([
            'customer.id',
            'customer.username',
            'customer.funds',
            'customer.payment_terms',
            'logg.*'
        ]);
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

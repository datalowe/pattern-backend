<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Scooter; // model of table
use Illuminate\Http\Request; // for POST route

class ScooterController extends Controller
{
    // $id from web.php contains scooter_id, $body contains key-value from POST
    public function updateScooter($id, Request $body)
    {
        // find scooter by its primary key
        $scooter = Scooter::find($id);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $scooter->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $scooter->$column = $value;
        }

        // update scooter
        $scooter->save();
    }

}

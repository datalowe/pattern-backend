<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Station; // model of table
use Illuminate\Http\Request; // for POST route

class StationController extends Controller
{
    // $id from api.php contains city_id, $body contains key-value from POST
    public function updateStation($id, Request $body)
    {
        // find Station by its primary key
        $station = Station::find($id);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $station->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $station->$column = $value;
        }

        // update station
        $station->save();
    }

}

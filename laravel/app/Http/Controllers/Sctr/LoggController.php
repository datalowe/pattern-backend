<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Logg; // model of table
use Illuminate\Http\Request;

// for POST route

class LoggController extends Controller
{
    // $id from web.php contains logg_id, $body contains key-value from POST
    public function updateLogg($id, Request $body)
    {
        // find Logg by its primary key
        $logg = Logg::find($id);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $logg->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $logg->$column = $value;
        }

        // update user
        $logg->save();
    }
}

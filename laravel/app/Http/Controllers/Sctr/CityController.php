<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\City; // model of table
use Illuminate\Http\Request;

// for POST route

class CityController extends Controller
{
    // $id from web.php contains scooter_id, $body contains key-value from POST
    public function updateCity($idNr, Request $body)
    {
        // find Station by its primary key
        $city = City::find($idNr);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $city->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $city->$column = $value;
        }

        // update city
        $city->save();
    }

    public function getAllCities()
    {
        return City::all();
    }

    public function getSingleCity($id)
    {
        return City::where('id', $id)->get();
    }

    public function getLinkedScooters($id)
    {
        return City::find($id)->scooters;
    }

    public function getLinkedStations($id)
    {
        return City::find($id)->stations;
    }
}

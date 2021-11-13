<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\City; // model of table
use Illuminate\Http\Request; // for POST route

class CityController extends Controller
{
    // $id from web.php contains scooter_id, $body contains key-value from POST
    public function updateCity($id, Request $body)
    {
        // find scooter by its primary key
        $city = City::find($id);

        // update specific column in row if body contains key-value
        isset($body->name) ? $city->name = $body->name : null; // assign $body->name
        isset($body->lat_center) ? $city->lat_center = $body->lat_center : null;
        isset($body->lon_center) ? $city->lon_center = $body->lon_center : null;
        isset($body->radius) ? $city->radius = $body->radius : null;

        // update city
        $city->save();
    }
}

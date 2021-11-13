<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Station; // model of table
use Illuminate\Http\Request; // for POST route

class StationController extends Controller
{
    // $id from web.php contains city_id, $body contains key-value from POST
    public function updateStation($id, Request $body)
    {
        // find scooter by its primary key
        $station = Station::find($id);

        // update specific column in row if body contains key-value
        isset($body->location) ? $station->location = $body->location : null; // assign $body->location
        isset($body->lat_center) ? $station->lat_center = $body->lat_center : null;
        isset($body->lon_center) ? $station->lon_center = $body->lon_center : null;
        isset($body->radius) ? $station->radius = $body->radius : null;
        isset($body->charge) ? $station->charge = $body->charge : null;

        // update station
        $station->save();
    }

}

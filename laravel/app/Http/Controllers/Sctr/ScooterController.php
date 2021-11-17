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

        // update specific column in row if body contains key-value
        if ($body->customer_id != null && $body->customer_id == "cancel") {
            $scooter->customer_id = null;
        } elseif ($body->customer_id != null) {
            $scooter->customer_id = $body->customer_id;
        }; // assign $body->customer_id

        isset($body->city_id) ? $scooter->city_id = $body->city_id : null;
        // update specific column in row if body contains key-value
        if ($body->station_id != null && $body->station_id == "cancel") {
            $scooter->station_id = null;
        } elseif ($body->station_id != null) {
            $scooter->station_id = $body->station_id;
        }; // assign $body->station_id

        isset($body->lat_pos) ? $scooter->lat_pos = $body->lat_pos : null;
        isset($body->lon_pos) ? $scooter->lon_pos = $body->lon_pos : null;
        isset($body->active) ? $scooter->active = $body->active : null;
        isset($body->speed_kph) ? $scooter->speed_kph = $body->speed_kph : null;
        isset($body->battery_level) ? $scooter->battery_level = $body->battery_level : null;
        isset($body->status) ? $scooter->status = $body->status : null;

        // update scooter
        $scooter->save();
    }

}

<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Logg; // model of table
use Illuminate\Http\Request; // for POST route

class LoggController extends Controller
{
    // $id from web.php contains logg_id, $body contains key-value from POST
    public function updateLogg($id, Request $body)
    {
        // find scooter by its primary key
        $logg = Logg::find($id);

        // update specific column in row if body contains key-value
        isset($body->customer_id) ? $logg->customer_id = $body->customer_id : null; // assign $body->customer_id
        isset($body->scooter_id) ? $logg->scooter_id = $body->scooter_id : null;
        isset($body->start_time) ? $logg->start_time = $body->start_time : null;
        isset($body->end_time) ? $logg->end_time = $body->end_time : null;
        isset($body->start_lat) ? $logg->start_lat = $body->start_lat : null;
        isset($body->start_lon) ? $logg->start_lon = $body->start_lon : null;
        isset($body->end_lat) ? $logg->end_lat = $body->end_lat : null;
        isset($body->end_lon) ? $logg->end_lon = $body->end_lon : null;
        isset($body->start_cost) ? $logg->start_cost = $body->start_cost : null;
        isset($body->travel_cost) ? $logg->travel_cost = $body->travel_cost : null;
        isset($body->parking_cost) ? $logg->parking_cost = $body->parking_cost : null;
        isset($body->total_cost) ? $logg->total_cost = $body->total_cost : null;

        // update user
        $logg->save();
    }
}

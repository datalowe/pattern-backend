<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Station; // model of table
use Illuminate\Http\Request;

// for POST route

class StationController extends Controller
{
    public function getAllStations()
    {
        return Station::all();
    }

    public function getSingleStation($idNr)
    {
        return Station::where('id', $idNr)->get();
    }

    public function getLinkedScooters($idNr)
    {
        return Station::find($idNr)->scooters;
    }
}

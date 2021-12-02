<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Adm; // model of table
use Illuminate\Http\Request;

// for POST route

class AdmController extends Controller
{

    // $id from web.php contains city_id, $body contains key-value from POST
    public function updateAdmin($id, Request $body)
    {
        // find scooter by its primary key
        $adm = Adm::find($id);

        // update specific column in row if body contains key-value
        isset($body->username) ? $adm->username = $body->username : null; // assign $body->username
        isset($body->token) ? $adm->token = $body->token : null;

        // update adm
        $adm->save();
    }
}

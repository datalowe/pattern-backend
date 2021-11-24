<?php

namespace App\Custom\Auth\Associate;

use Illuminate\Http\Request;

class Associate {
    public function isAssociateReq(Request $req) {
        // TODO change way that token is extracted - it shouldn't be stored
        // as a cookie, instead it should probably be plucked from a header
        // or something.
        $token = $req->cookie('associate_token');

        // check if user actually has 'associate_token' cookie
        if (! $token) {
            return false;
        }

        // TODO add logic for checking user's token - what kind of token
        // to generate, and how to store it (if at all) or at least check it
        // is still to be determined.
        return true;
    }
}
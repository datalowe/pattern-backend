<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Associate extends Model
{
    protected $table = 'apikeys'; // table name
    protected $primaryKey = 'client';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at
    protected $hidden = ['apikey'];

    public static function isAssociateReq(Request $req)
    {
        $apiKey = $req->header('api-key');

        // check if user actually has 'associate-token' header
        if (! $apiKey) {
            return false;
        }

        // check if user's token is in the database's adm table.
        if (! self::firstWhere('apikey', $apiKey)) {
            return false;
        }

        return true;
    }
}

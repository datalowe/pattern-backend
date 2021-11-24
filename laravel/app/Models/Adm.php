<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Adm extends Model
{
    protected $table = 'adm'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at

    // mass assignable properties - unsure what means
    // columns in table 'adm'
    protected $fillable = [
        'username',
        'password'
    ];

    public static function isAdmReq(Request $req) {
        $token = $req->cookie('oauth_token');

        // check if user actually has 'oauth_token' cookie
        if (! $token) {
            return false;
        }
        // TODO add logic for checking if user's token is in
        // admin table 'token' column
        return false;
        return true;
    }

    public static function reqToAdm(Request $req) {
        return self::firstWhere('token', $req->cookie('oauth_token'));
    }
}

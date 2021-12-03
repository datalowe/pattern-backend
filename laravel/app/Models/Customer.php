<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Customer extends Model
{
    protected $table = 'customer'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at
    protected $hidden = ['token'];

    // mass assignable properties - unsure what means exactly, but at least it
    // means these can be used when creating customers using Customer::create
    // columns in table 'customer'
    protected $fillable = [
        'username',
        'token'
        // 'funds',
        // 'payment_terms'
    ];

    public static function isCustomerReq(Request $req)
    {
        $token = $req->cookie('oauth_token');

        // check if user actually has 'oauth_token' cookie
        if (! $token) {
            return false;
        }
        // check if user's token is in the database's customer table.
        if (! self::firstWhere('token', $token)) {
            return false;
        }
        return true;
    }

    public static function reqToCustomer(Request $req)
    {
        return self::firstWhere('token', $req->cookie('oauth_token'));
    }
}

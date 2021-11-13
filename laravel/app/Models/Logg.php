<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logg extends Model
{
    protected $table = 'logg'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at
    protected $addHttpCookie = true; // send csrf token for post requests in order to work with post/put

    // mass assignable properties - unsure what means
    // columns in table 'city'
    protected $fillable = [
        'customer_id',
        'scooter_id',
        'start_time',
        'end_time',
        'start_lat',
        'start_lon',
        'end_lat',
        'end_lon',
        'start_cost',
        'travel_cost',
        'parking_cost',
        'total_cost'
    ];
}

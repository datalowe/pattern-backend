<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'station'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at
    protected $addHttpCookie = true; // send csrf token for post requests in order to work with post/put

    // mass assignable properties - unsure what means
    // columns in table 'station'
    protected $fillable = [
        'city_id',
        'location',
        'lat_center',
        'lon_center',
        'radius',
        'type'
    ];
}

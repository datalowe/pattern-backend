<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scooter extends Model
{
    protected $table = 'scooter'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at

    // mass assignable properties - unsure what means
    // columns in table 'scooter'
    protected $fillable = [
        'customer_id',
        'city_id',
        'station_id',
        'lat_pos',
        'lon_pos',
        'speed_kph',
        'battery_level',
        'status'
    ];
}

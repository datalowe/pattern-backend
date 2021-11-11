<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scooter extends Model
{
    use HasFactory;

    protected $table = 'scooter';

    public $timestamps = false;

    // 'fillable' should be used to specify what attributes are 'mass assignable'. I can't
    // quite remember what that means, but leaving as comment here to make easier keeping track
    // of what database columns are in the scooter table.
    // protected $fillable = [
    //     'customer_id',
    //     'city_id',
    //     'station_id',
    //     'rented',
    //     'lat_pos',
    //     'lon_pos',
    //     'maintenance_mode',
    //     'active',
    //     'speed',
    //     'battery_level'
    // ];
}

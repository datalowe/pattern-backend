<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scooter;
use App\Models\Station;

class City extends Model
{
    protected $table = 'city'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at

    // mass assignable properties - unsure what means
    // columns in table 'city'
    protected $fillable = [
        'name',
        'lat_center',
        'lon_center',
        'radius'
    ];

    public function scooters()
    {
        return $this->hasMany(Scooter::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}

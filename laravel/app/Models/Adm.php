<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adm extends Model
{
    protected $table = 'adm'; // table name
    protected $primaryKey = 'id';
    public $timestamps = false; // stops laravel from adding fields created_at and updated_at
    protected $addHttpCookie = true; // send csrf token for post requests in order to work with post/put

    // mass assignable properties - unsure what means
    // columns in table 'adm'
    protected $fillable = [
        'username',
        'password'
    ];
}

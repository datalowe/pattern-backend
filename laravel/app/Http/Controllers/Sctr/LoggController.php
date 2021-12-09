<?php

namespace App\Http\Controllers\Sctr;

use App\Http\Controllers\Controller;
use App\Models\Logg; // model of table
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

// for POST route

class LoggController extends Controller
{
    public function getAllLogs()
    {
        return DB::table('logg')
        ->leftjoin('customer', 'logg.customer_id', '=', 'customer.id')
        ->orderBy('logg.id', 'asc')
        ->get([
            'logg.*',
            'customer.username',
            'customer.payment_terms'
        ]);
    }
}

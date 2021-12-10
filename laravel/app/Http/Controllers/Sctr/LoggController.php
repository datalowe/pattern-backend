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
        ->select('logg.*', 'customer.username', 'customer.payment_terms')
        ->whereNotNull('end_time')
        ->orderBy('logg.id', 'asc')
        ->get();
    }
}

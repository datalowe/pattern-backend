<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Adm;
use App\Models\Customer;

class EnsureIsAdminOrOwner
{
    /**
     * Check if the client is an administrator or the customer itself that the request
     * relates to (used for e. g. user payment details endpoints).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Adm::isAdmReq($request)) {
            return $next($request);
        }

        if (! Customer::isCustomerReq($request)) {
            return response(['error' => 'Route requires admin or customer authentication'], 401);
        }

        $ownerId = $request->route('id');
        $requestCustomer = Customer::reqToCustomer($request);
        if ($requestCustomer->id == $ownerId) {
            return $next($request);
        }
        
        return response(['error' => 'As a customer, you may not access other customers\' data'], 403);
    }
}

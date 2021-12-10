<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Adm;
use App\Models\Associate;
use App\Models\Customer;

class EnsureHasValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $isCustomer = Customer::isCustomerReq($request);
        $isAdmin = Adm::isAdmReq($request);
        $isAssociate = Associate::isAssociateReq($request);

        if ($isCustomer || $isAdmin || $isAssociate) {
            return $next($request);
        }
        return response(['error' => 'Route requires authentication'], 401);
    }
}

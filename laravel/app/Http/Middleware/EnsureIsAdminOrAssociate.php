<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Adm;
use App\Models\Associate;

class EnsureIsAdminOrAssociate
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
        $isAdmin = Adm::isAdmReq($request);
        $isAssociate = Associate::isAssociateReq($request);

        if ($isAdmin || $isAssociate) {
            return $next($request);
        }
        return response(['error' => 'Route requires admin or associate authentication'], 401);
    }
}

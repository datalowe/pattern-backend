<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Adm;

class EnsureIsAdmin
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

        if ($isAdmin) {
            return $next($request);
        }
        return response(['error' => 'Route requires admin authentication'], 401);
    }
}

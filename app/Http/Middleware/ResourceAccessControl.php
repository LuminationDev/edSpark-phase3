<?php

namespace App\Http\Middleware;

use App\Services\ResponseService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceAccessControl
{
    public function handle($request, Closure $next, ...$parameters)
    {
        // Extract role and allowedMethods from parameters
        $role = array_shift($parameters);
        // might be worthwhile to do disallowed methods instead of allowed
        $allowedMethods = $parameters;

        // Check if the user has the role. If true, subject to the check
        if (strtolower(Auth::user()->role->role_name) === $role) {
            $currentMethod = $request->route()->getActionMethod();
            if (!in_array($currentMethod, $allowedMethods)) {
                return ResponseService::error('Unauthorized', 'Forbidden', 403 );
            }
        }

        return $next($request);
    }
}
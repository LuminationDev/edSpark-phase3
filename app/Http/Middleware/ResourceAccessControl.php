<?php

namespace App\Http\Middleware;

use App\Services\ResponseService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceAccessControl
{

    protected $role;
    protected $allowedMethods;

    public function handle($request, Closure $next, ...$parameters)
    {
        // Extract role and allowedMethods from parameters
        $this->role = array_shift($parameters);
        $this->allowedMethods = $parameters;

        // Check if the user has the role. If true, subject to the check
        if (strtolower(Auth::user()->role->role_name) === $this->role) {
            $currentMethod = $request->route()->getActionMethod();
            if (!in_array($currentMethod, $this->allowedMethods)) {
                return ResponseService::error('Unauthorized', 'Forbidden', 403 );
            }
        }

        return $next($request);
    }
}
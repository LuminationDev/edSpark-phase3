<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        /**
         * To enable stateless CSRF protection
         * adding your frontend domain
         */
        // 'http://localhost:8000'
        // '/admin',
        // '/admin/login'
        '/logout'
    ];
}

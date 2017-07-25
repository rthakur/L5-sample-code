<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/cdn/*',
        '/api/property',
        '/api/property/*',
        '/api/agency',
        '/api/agency/*',
        '/api/agent',
        '/api/agent/*',
        '/api/error',
        '/api/error/*',
        '/stripeevents',
        '/buy/property'
    ];
}

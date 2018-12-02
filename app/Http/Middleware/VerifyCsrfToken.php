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

        'admin/api/get-action-diagram',
        'admin/api/update-instance',
        'admin/api/get-instance-form',
        'admin/api/get-instance-context-menu',
    ];
}

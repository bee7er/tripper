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

        'admin/api/delete-instance',
        'admin/api/get-action-diagram',
        'admin/api/get-instance-form',
        'admin/api/get-instance-context-menu',
        'admin/api/save-instance',
        'admin/api/selected-question',
        'admin/api/selected-snippet',
        'admin/api/send-action',
    ];
}

<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware {

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'search/category',
        'search/models',
        'model/preview',
        'search/suppliers',
        'supplier/preview',
        'search/locations',
        'location/preview',
        'model/create',
        '/photo/upload',
        '/import/properties/errors',
        '/permissions/users',
    ];

}

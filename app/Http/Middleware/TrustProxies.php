<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    // protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $proxies = '*';
    protected $headers = Request:: HEADER_X_FORWARDED_AWS_ELB;
    // protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
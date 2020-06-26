<?php
namespace App\Http\Middleware;

use Closure;

class NoIframe {

    public function handle($request, closure $next) {
        $response = $next($request);

        $response->header('X-Frame-Options', 'SAMEORIGIN');

        return $response;
    }

}
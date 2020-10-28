<?php

namespace dimonka2\flatform;

use Closure;

class FlatformMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $stale
     * @return mixed
     */
    public function handle($request, Closure $next, $style)
    {
        Flatform::setOptions(['style' => $style]);

        return $next($request);
    }

}

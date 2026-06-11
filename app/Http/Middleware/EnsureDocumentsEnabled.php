<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDocumentsEnabled
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(config('documents.enabled'), 404);

        return $next($request);
    }
}

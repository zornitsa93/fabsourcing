<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserApproved {
    public function handle(Request $request, Closure $next) {
        $lang = $request->route('lang') ?? 'fr';
        if (! Auth::check() || ! Auth::user()->isApproved()) {
            return redirect()->route('login', $lang);
        }
        return $next($request);
    }
}

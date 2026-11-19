<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Language;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $lang    = $request->route('lang');
        $allowed = cache()->remember(
            'active_locale_slugs',
            now()->addHours(1),
            fn () => Language::active()->pluck('slug')->toArray()
        );

        // A language prefix that is not an active language (/bg, /de ...) must not serve the
        // French text under another URL (duplicate content): 404.
        if ($lang !== null && !in_array($lang, $allowed, true)) {
            abort(404);
        }

        // Fall back to 'fr' if the URL param is missing
        $locale = in_array($lang, $allowed, true) ? $lang : 'fr';

        App::setLocale($locale);

        return $next($request);
    }
}

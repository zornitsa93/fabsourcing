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

        // Fall back to 'fr' if the URL param is missing or unrecognised
        $locale = in_array($lang, $allowed, true) ? $lang : 'fr';

        App::setLocale($locale);

        return $next($request);
    }
}

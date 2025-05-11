<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    /**
     * Handle an incoming request and set the application locale based on the Accept-Language header.
     *
     * This middleware checks the 'Accept-Language' HTTP header and sets the application's locale accordingly.
     * If the header is missing or the locale is not supported, it falls back to the default locale
     * defined in the application configuration ('config/app.php').
     *
     * Example:
     * - Accept-Language: 'es' → App::setLocale('es')
     * - Accept-Language: 'en' → App::setLocale('en')
     * - Accept-Language: 'fr' → App::setLocale('es') (fallback to default)
     *
     * @param  \Illuminate\Http\Request  $request  The current HTTP request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  The next middleware or request handler.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language');

        if (!in_array($locale, ['en', 'es'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        return $next($request);
    }
}

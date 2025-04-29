<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        
        $locale = session('locale');
        Log::info('Middleware - Current session locale: ' . ($locale ?? 'not set'));

        
        $supportedLocales = ['en', 'es', 'fr', 'ar'];
        App::setLocale(in_array($locale, $supportedLocales) ? $locale : config('app.locale'));

        Log::info('Middleware - Locale set to: ' . App::getLocale());

        return $next($request);
    }
}

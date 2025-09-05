<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Symfony\Component\HttpFoundation\Response;

class RedirectPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        if (auth()->check()) {
            $panel = Filament::getCurrentPanel();
            $user = auth()->user();

            if ($panel && ! $user->canAccessPanel($panel)) {
                // arahkan ke panel yang cocok
                foreach (filament()->getPanels() as $p) {
                    if ($user->canAccessPanel($p)) {
                        return redirect($p->getUrl());
                    }
                }
                return redirect('/');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            if (!$request->is('change-password') && !$request->is('logout')) {
                return redirect()->route('change-password')
                    ->with('warning', 'Anda harus mengubah password default terlebih dahulu!');
            }
        }

        return $next($request);
    }
}

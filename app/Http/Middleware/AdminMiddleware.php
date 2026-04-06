<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (strtolower(auth()->user()->role) !== 'admin') {
    abort(403, 'Anda bukan admin');
}

        return $next($request);
    }
}

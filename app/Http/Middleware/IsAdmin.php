<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            return $this->buildForbiddenResponse($request);
        }

        return $next($request);
    }

    private function buildForbiddenResponse(Request $request): Response|RedirectResponse
    {
        if ($request->expectsJson()) {
            abort(403, 'Administrator access is required.');
        }

        return redirect('/');
    }
}

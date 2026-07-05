<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->withErrors(['email' => 'Please use the admin dashboard.']);
        }

        return $next($request);
    }
}

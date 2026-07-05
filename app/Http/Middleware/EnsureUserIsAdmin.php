<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            // Not logged in → they were trying to reach an admin page, send them to admin login
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Please log in to continue.']);
        }

        if (!$user->isAdmin()) {
            // Logged in as a customer → login page is pointless, send them somewhere real
            return redirect()
                ->route('main-page')
                ->withErrors(['email' => 'You do not have access to that page.']);
        }

        return $next($request);
    }
}

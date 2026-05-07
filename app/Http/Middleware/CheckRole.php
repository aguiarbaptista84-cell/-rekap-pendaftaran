<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!empty($roles) && !in_array(auth()->user()->role, $roles)) {
            abort(403, 'La iha permisaun atu asesu pájina ne\'e.');
        }

        if (!auth()->user()->aktif) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Konta ita boot la ativu. Kontaktu administrator.');
        }

        return $next($request);
    }
}

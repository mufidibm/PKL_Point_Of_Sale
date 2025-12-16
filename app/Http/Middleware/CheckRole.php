<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('login');
        }

        $userRole = $request->user()->role; // asumsi kolom di tabel users = 'role'

        foreach ($roles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Kalau tidak punya role yang diizinkan
        abort(403, 'Akses ditolak. Kamu bukan ' . implode(' atau ', $roles));
    }
}

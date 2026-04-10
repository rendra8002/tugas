<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPetugasOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user yang login rolenya BUKAN petugas, lempar ke halaman 403 (Unauthorized)
        if (Auth::user() && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak! Hanya petugas yang boleh mengelola buku.');
        }

        return $next($request);
    }
}

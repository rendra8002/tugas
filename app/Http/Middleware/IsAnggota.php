<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAnggota
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (Auth::check()) {
            // Cek apakah role-nya anggota
            if (Auth::user()->role == 'anggota') {
                return $next($request);
            }

            // Kalau admin/petugas coba masuk ke area anggota, lempar balik ke dashboard admin
            return redirect('/admin/dashboard')->with('error', 'Petugas tidak dapat melakukan peminjaman dari halaman ini.');
        }

        return redirect('/login');
    }
}

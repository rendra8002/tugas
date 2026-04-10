<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminOrPetugas
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (Auth::check()) {
            // Cek apakah role-nya kepala_perpustakaan atau petugas
            if (Auth::user()->role == 'kepala_perpustakaan' || Auth::user()->role == 'petugas') {
                return $next($request);
            }
            
            // Kalau bukan (berarti anggota), lempar ke halaman depan (Frontend)
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Kalau belum login, lempar ke login
        return redirect('/login');
    }
}
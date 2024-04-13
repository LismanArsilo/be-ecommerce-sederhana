<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            Log::debug($request->all());
            // Cek apakah header Authorization tersedia dalam permintaan
            if (!$request->header('Authorization')) {
                // Jika tidak ada header Authorization, arahkan ke halaman login
                return route('unauthenticated');
            } else {
                Log::debug($request->header('Authorization'));
                // Jika header Authorization tersedia, tetapi token tidak valid, arahkan ke halaman login
                // Anda perlu menambahkan logika untuk memeriksa kevalidan token di sini
                // Misalnya, jika menggunakan Laravel Sanctum, Anda bisa memeriksa kevalidan token Sanctum di sini
                if (!auth()->check()) {
                    return route('validate');
                }
            }
        }

        return null;
        // return $request->expectsJson() ?? route('unauthenticated');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header('API-KEY')) {
            return response()->json(['status' => false, 'message' => 'Invalid API'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $apiKey = $request->header('API-KEY');
        $key = env('API_KEY');

        if ($apiKey !== $key) {
            return response()->json(['status' => false, 'message' => 'Key Not Falid'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $next($request);
    }
}

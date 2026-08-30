<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach HTTP security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent MIME Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Clickjacking Protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Privacy-focused Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict unnecessary browser APIs (Camera, Microphone, Geolocation, etc.)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');

        // Strict Content Security Policy (allows local assets, CDNs, & Google Maps used by PixelVault)
        $csp = "default-src 'self'; "
             . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ajax.googleapis.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://maps.googleapis.com; "
             . "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://cdn.datatables.net http://fonts.cdnfonts.com https://fonts.cdnfonts.com; "
             . "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com http://fonts.cdnfonts.com https://fonts.cdnfonts.com data:; "
             . "img-src 'self' data: https:; "
             . "connect-src 'self' https://maps.googleapis.com; "
             . "frame-src 'self' https://maps.google.com https://www.google.com; "
             . "frame-ancestors 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}

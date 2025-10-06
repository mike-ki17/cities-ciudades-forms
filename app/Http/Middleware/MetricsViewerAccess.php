<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetricsViewerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        // Permitir acceso a usuarios con rol metrics_viewer o admin
        if (!$request->user()->isMetricsViewer() && !$request->user()->isAdmin()) {
            abort(403, 'Acceso denegado. Se requieren permisos de visualización de métricas.');
        }

        return $next($request);
    }
}

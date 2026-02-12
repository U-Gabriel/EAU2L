<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrackMovements
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // On n'enregistre que les requêtes de pages (pas les images ou API internes)
        if ($request->isMethod('get') && !$request->ajax()) {
            try {
                DB::table('movement_available')->insert([
                    'session_id'      => session()->getId(),
                    'visitor_ip'      => $request->ip(),
                    'concerning_page' => $request->fullUrl(),
                    'referrer_url'    => $request->headers->get('referer'),
                    'user_agent'      => $request->userAgent(),
                    'device_type'     => $this->getDeviceType($request->userAgent()),
                    'user_id'         => auth()->id() ?? null,
                    'created_at'      => now(),
                ]);
            } catch (\Exception $e) {
                // On log l'erreur mais on ne bloque pas l'utilisateur
                report($e);
            }
        }

        return $response;
    }

    private function getDeviceType($agent)
    {
        $agent = strtolower($agent);
        if (str_contains($agent, 'mobile')) return 'mobile';
        if (str_contains($agent, 'tablet')) return 'tablet';
        return 'desktop';
    }
}
<?php

namespace App\Http\Middleware;

use App\Models\BehaviorEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackBehavior
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('tracking_session_id')) {
            $request->session()->put('tracking_session_id', (string) str()->uuid());
        }

        if (Schema::hasTable('behavior_events') && $request->isMethod('get') && ! $request->is('admin*')) {
            BehaviorEvent::create([
                'user_id' => optional($request->user())->id,
                'session_id' => $request->session()->get('tracking_session_id'),
                'event_type' => 'visit',
                'source' => $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'occurred_at' => now(),
            ]);
        }

        if ($request->user()) {
            $request->user()->forceFill(['last_seen_at' => now()])->save();
        }

        return $next($request);
    }
}

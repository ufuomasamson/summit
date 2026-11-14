<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Settings;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerifiedIfEnabled
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $settings = Settings::where('id', 1)->first();

        if (!$settings || $settings->enable_verification !== 'true') {
            return $next($request);
        }

        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : redirect()->route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }
}

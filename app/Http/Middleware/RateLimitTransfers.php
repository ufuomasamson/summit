<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RateLimitTransfers
{
    /**
     * Maximum transfers allowed within time window
     */
    const MAX_TRANSFERS = 10;

    /**
     * Time window in minutes
     */
    const TIME_WINDOW = 60;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->id();
        $cacheKey = "transfer_count_{$userId}";
        
        // Get current count
        $count = Cache::get($cacheKey, 0);
        
        // Check if limit exceeded
        if ($count >= self::MAX_TRANSFERS) {
            return back()->with('message', 'Transfer limit exceeded. You can make ' . self::MAX_TRANSFERS . ' transfers per hour. Please try again later.');
        }
        
        // Increment count
        Cache::put($cacheKey, $count + 1, now()->addMinutes(self::TIME_WINDOW));
        
        return $next($request);
    }

    /**
     * Reset transfer count for a user (admin use)
     *
     * @param int $userId
     * @return void
     */
    public static function reset(int $userId): void
    {
        Cache::forget("transfer_count_{$userId}");
    }
}

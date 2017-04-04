<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class NotLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get user object
        $user = (empty($request)) ? auth()->user() : $request->user();
        // Determine if the user is locked out.
        if($user->locked == true) {
            // Log off user with message.
            Auth::logout();
            return redirect(url('/login'))->with('error', 'This account has been locked by the system admin.');
        }
        return $next($request);
    }
}

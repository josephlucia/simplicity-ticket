<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckRole
{
    /**
     * Check to make sure the user has the proper role to perform the action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Get user object
        $user = (empty($request)) ? auth()->user() : $request->user();
        // Determine if the user is locked out.
        if($user->locked == true) {
            // Log off user with message.
            Auth::logout();
            return redirect(url('/login'))->with('error', 'This account has been locked by the system admin.');
        }
        // Determine if they are authorized
        if(!$user->hasRole($user->id, $role)) {
            // User not authorized
            abort(404);
        }
        // Keep moving
        return $next($request);
    }
}

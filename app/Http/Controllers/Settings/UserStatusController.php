<?php

namespace App\Http\Controllers\Settings;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Lock the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lock($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Lock the user.
        $user = User::findOrFail($id);
        $user->update([
        	'locked' => true
        ]);
    }

    /**
     * Unlock the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlock($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Unlock the user.
        $user = User::findOrFail($id);
        $user->update([
        	'locked' => false
        ]);
    }
}

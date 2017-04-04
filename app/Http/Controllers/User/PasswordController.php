<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the password profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate the request.
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        // Change the password.
        if(Hash::check($request->current_password, auth()->user()->password)) {
        	$request->user()->update([
        		'password' => bcrypt($request->password)
        	]);

        	return back()->with('success', 'Your password was successfully changed.');
        } else {
        	return back()->withErrors([
                'errors' => 'The current password does not match what is on file.'
            ]);
        }
    }
}

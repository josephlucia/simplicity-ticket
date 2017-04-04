<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
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
     * Display the profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = auth()->user();

        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate the request.
        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => ['required', 'email', 'max:191', Rule::unique('users')->ignore(auth()->user()->id)]
        ]);
        // Update the profile info.
        $request->user()->update([
        	'name' => $request->name,
        	'email' => $request->email
        ]);
        
        return back()->with('success', 'Your profile was successfully updated.');
    }
}

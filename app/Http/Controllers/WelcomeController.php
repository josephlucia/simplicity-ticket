<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Department;
use App\DepartmentUser;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Check if there is an admin user, otherwise go to login.
     *
     * @return mixed
     */
    public function index()
    {
    	// Check if there are any admin users.
    	if (User::where('role', 'admin')->count() == 0) {
    		return view('setup.wizard');
    	}
        // If authenticated, redirect to the users dashboard.
        if (Auth::check()) {
            return auth()->user()->role == 'user' 
            			? redirect(url('/home')) 
            			: redirect(url('/tickets'));
        } 
        
        return view('auth.login');
    }

    /**
     * Create the admin user to access the system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request.
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        // Create the admin user.
        $user = User::create([
            'role' => 'admin',
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // Create a default department.
        $department = Department::create([
        	'name' => 'Technology Support'
        ]);
        // Bind the admin user to the default department.
        $department_user = DepartmentUser::create([
            'department_id' => $department->id,
            'user_id' => $user->id
        ]);
        // Log the admin in.
        Auth::loginUsingId($user->id);

        return redirect(url('/settings'));
    }
}

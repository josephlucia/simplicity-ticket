<?php

namespace App\Http\Controllers\Settings;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisteredController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.registered.index');
    }

    /**
     * Display all registered users.
     *
     * @param  int  $rows
     * @return \Illuminate\Http\Response
     */
    public function all($rows)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Get all users.
        $users = User::where('role', 'user')
        			 ->orderBy('name', 'asc')
        			 ->paginate($rows);

        return $users;
    }

    /**
     * Remove the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Find the user by id.
        $user = User::findOrFail($id);
        // Delete the user.
        $user->delete();
    }
}

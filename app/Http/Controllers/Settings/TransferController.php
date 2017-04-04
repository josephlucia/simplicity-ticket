<?php

namespace App\Http\Controllers\Settings;

use App\User;
use App\Ticket;
use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
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
    	// Get all staff and admin users.
    	$users = User::where('role', '!=', 'user')->orderBy('name')->get();
    	// Get all departments.
    	$departments = Department::orderBy('name')->get();

        return view('settings.transfer.index', compact('users', 'departments'));
    }

    /**
     * Transfer tickets between staff members.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function staff(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
        	'from' => 'required|integer',
        	'to' => 'required|integer'
        ]);
        // Transfer the tickets to the new user.
        $tickets = Ticket::where('assigned_to', $request->from)->update(['assigned_to' => $request->to]);

        return back()->with('success', 'The tickets were successfully transferred to the new user.');
    }

    /**
     * Transfer tickets between departments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function departments(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
        	'from' => 'required|integer',
        	'to' => 'required|integer'
        ]);
        // Transfer the tickets to the new user.
        $tickets = Ticket::where('department_id', $request->from)->update(['department_id' => $request->to]);

        return back()->with('success', 'The tickets were successfully transferred to the new department.');
    }
}

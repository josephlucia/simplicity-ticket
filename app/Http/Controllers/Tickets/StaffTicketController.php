<?php

namespace App\Http\Controllers\Tickets;

use App\User;
use App\Ticket;
use App\Comment;
use App\Department;
use App\DepartmentUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffTicketController extends Controller
{
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('staff');
        $this->middleware('unlocked');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.staff');
    }

    /**
     * Display all staff tickets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $rows
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request, $rows, $status)
    {
        // Status to resolved.
        $resolved = $status == 'open' ? false : true;
        // Search value if present.
        $search = isset($request->search) ? $request->search : null;
    	// Get staff departments.
    	$departments = DepartmentUser::where('user_id', auth()->user()->id)->get()->pluck('department_id');
        // Get all staff tickets.
        $tickets = Ticket::with('department')
        				 ->whereIn('department_id', $departments)
                         ->orWhere('assigned_to', auth()->user()->id)
                         ->search($search)
                         ->where('resolved', $resolved)
                         ->orderBy('created_at', 'desc')
                         ->paginate($rows);

        return $tickets;
    }

    /**
     * Display all departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function departments()
    {
        // Get all departments.
        $departments = Department::orderBy('name')->get();

        return $departments;
    }

    /**
     * Display all staff members.
     *
     * @return \Illuminate\Http\Response
     */
    public function members()
    {
        // Get all staff.
        $staff = User::with('departments')
                     ->where('role', '!=', 'user')
                     ->orderBy('role', 'asc')
                     ->orderBy('name', 'asc')
                     ->get();

        return $staff;
    }

    /**
     * Display all registered users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        // Get all users.
        $users = User::where('role', 'user')
                     ->orderBy('name', 'asc')
                     ->get();

        return $users;
    }

    /**
     * Display the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$staff = User::where('role', '!=', 'user')->orderBy('name')->get();
        $ticket = Ticket::findOrFail($id);

        return view('tickets.show', compact('ticket', 'staff'));
    }

    /**
     * Assign ticket to staff.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign(Request $request, $id)
    {
        // Validate the request.
        $this->validate($request, [
            'member' => 'required|integer'
        ]);
        // Find ticket.
        $ticket = Ticket::findOrFail($id);
        // Assign ticket to staff member.
        $ticket->assigned_to = $request->member;
        $ticket->save();
        // Add comment.
        $comment = Comment::create([
            'ticket_id' => $id,
            'details' => 'Ticket has been assigned to '.$ticket->assignment->name.' by '.auth()->user()->name.'.',
            'user_id' => 0
        ]);
    }
}

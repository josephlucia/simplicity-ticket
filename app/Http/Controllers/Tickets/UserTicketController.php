<?php

namespace App\Http\Controllers\Tickets;

use App\User;
use Exception;
use App\Ticket;
use App\Comment;
use App\Department;
use App\Configuration;
use Illuminate\Http\Request;
use App\Mail\TicketOpened;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class UserTicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('unlocked');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index');
    }

    /**
     * Display all registered user's tickets.
     *
     * @param  int  $rows
     * @return \Illuminate\Http\Response
     */
    public function all($rows)
    {
        // Get all authenticated users tickets.
        $tickets = Ticket::mine()
                         ->with('department')
                         ->orderBy('created_at', 'desc')
                         ->paginate($rows);

        return $tickets;
    }

    /**
     * Display the ticket details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        // Get the ticket details.
        $ticket = Ticket::with('assignment')->findOrFail($id);
        // Permissions check.
        abort_unless(auth()->user()->can('view', $ticket), 404);

        return $ticket;
    }

    /**
     * Display the departments.
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
     * Store a new ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request.
        $this->validate($request, [
            'department' => 'required',
            'subject' => 'required|max:100',
            'room' => 'max:100',
            'phone' => 'max:25',
            'details' => 'required',
            'priority' => 'required|max:25',
        ]);
        // Create the ticket.
        $ticket = Ticket::create([
            'user_id' => $request->customer ?: auth()->user()->id,
            'department_id' => $request->department,
            'room' => $request->room,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'details' => $request->details,
            'priority' => $request->priority
        ]);
        // Generate the ticket number.
        $ticket->update([
            'number' => Ticket::number($ticket->id)
        ]);
        // Comment creator.
        $commentCreator = $request->customer 
                            ? auth()->user()->name.' on behalf of '.$ticket->owner->name 
                            : auth()->user()->name;
        // Add initial comment.
        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => 0,
            'details' => 'New ticket created by '.$commentCreator. '.'
        ]);
        // Send ticket creation email.
        if(! is_null(Configuration::value('send_email'))) {
            try {
                $user = User::findOrFail($request->customer ?: auth()->user()->id);
                Mail::to($user)->send(new TicketOpened($ticket));
            } catch (Exception $exception) {
                // Display the mail not send error.
                return response()->json([
                    'mailer' => $exception->getMessage()
                ], 422);
            }
        }
    }

    /**
     * Display the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::mine()->findOrFail($id);
        
        return view('tickets.show', compact('ticket'));
    }
}

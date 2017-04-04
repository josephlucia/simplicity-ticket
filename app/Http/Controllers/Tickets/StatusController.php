<?php

namespace App\Http\Controllers\Tickets;

use Exception;
use App\Ticket;
use App\Comment;
use App\Configuration;
use App\Mail\TicketClosed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('staff');
    }

    /**
     * Close an open ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close($id)
    {
        // Find ticket.
        $ticket = Ticket::findOrFail($id);
        // Change status to closed.
        $ticket->resolved = true;
        $ticket->save();
        // Add comment.
        $comment = Comment::create([
            'ticket_id' => $id,
            'details' => 'Ticket has been closed by '.auth()->user()->name.'.',
            'user_id' => 0
        ]);
        // Send ticket closed email.
        if(! is_null(Configuration::value('send_email'))) {
            try {
                Mail::to($ticket->owner)->send(new TicketClosed($ticket));
            } catch (Exception $exception) {
                // Display the mail not send error.
                return response()->json([
                    'errors' => 'Ticket closed successfully, but the email failed. Error: ' . $exception->getMessage() . '. Please contact your system administrator.'
                ], 422);
            }
        }
    }

    /**
     * Open a closed ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open($id)
    {
        // Find ticket.
        $ticket = Ticket::findOrFail($id);
        // Change status to closed.
        $ticket->resolved = false;
        $ticket->save();
        // Add comment.
        $comment = Comment::create([
            'ticket_id' => $id,
            'details' => 'Ticket has been re-opened by '.auth()->user()->name.'.',
            'user_id' => 0
        ]);
    }
}

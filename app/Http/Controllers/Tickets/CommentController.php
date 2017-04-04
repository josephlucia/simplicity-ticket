<?php

namespace App\Http\Controllers\Tickets;

use App\Ticket;
use App\Comment;
use App\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
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
     * Display the ticket's comments.
     *
     * @param  int  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function all($ticketId)
    {
        // Permissions check.
        $ticket = Ticket::findOrFail($ticketId);
        abort_unless(auth()->user()->can('view', $ticket), 404);
        // Get all ticket comments.
        $comments = Comment::where('ticket_id', $ticketId)
                           ->visible()
        				   ->orderBy('created_at', 'desc')
        				   ->get();

        return $comments;
    }

    /**
     * Store a new comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request.
        $this->validate($request, [
            'ticket' => 'required|integer',
            'details' => 'required',
        ]);
        // Create the comment.
        $comment = Comment::create([
            'ticket_id' => $request->ticket,
            'user_id' => auth()->user()->id,
            'details' => $request->details,
            'hidden' => $request->hidden ?: false
        ]);
    }

    /**
     * Update a comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request.
        $this->validate($request, [
            'details' => 'required',
        ]);
        // Find the comment by id.
        $comment = Comment::findOrFail($id);
        // Permissions check.
        abort_unless(auth()->user()->can('update', $comment), 404);
        // Update the comment.
        $comment->update([
            'details' => $request->details,
            'hidden' => $request->hidden ?: false
        ]);
    }

    /**
     * Remove a comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the comment by id.
        $comment = Comment::findOrFail($id);
        // Permissions check.
        abort_unless(auth()->user()->can('delete', $comment), 404);
        // Delete the comment.
        $comment->delete();
    }
}

@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<!-- Ticket Details -->
	<sts-ticket-details :ticket_id="{{ $ticket->id }}"></sts-ticket-details>

	<!-- Ticket Comments -->
	<sts-ticket-comments :ticket_id="{{ $ticket->id }}"></sts-ticket-comments>
</div>
@endsection

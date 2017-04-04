@component('mail::message')
## New Ticket Confirmation
### Ticket #{{ $ticket->number }}

Thank you for submitting a new ticket. Your case details are below.

**Subject:** {{ $ticket->subject }}

**Priority:** {{ $ticket->priority }}

**Details:** {{ $ticket->details }}

Thanks,<br>
Ticket Manager
@endcomponent

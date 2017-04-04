@component('mail::message')
## New Ticket Comment
### Ticket #{{ $ticket->number }}

A new comment was added to your ticket by {{ $comment->contributor }}.

@component('mail::panel')
{{ $comment->details }}
@endcomponent

Thanks,<br>
Ticket Manager
@endcomponent

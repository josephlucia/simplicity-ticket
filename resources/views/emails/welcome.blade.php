@component('mail::message')
# Welcome {{ $user->name }}

Your staff account has been created. You may log in with the temporary password listed below.
  
@component('mail::panel')
{{ $password }}
@endcomponent

@component('mail::button', ['url' => url('/login'), 'color' => 'green'])
Launch Application
@endcomponent

Thanks,<br>
Systems Admin
@endcomponent

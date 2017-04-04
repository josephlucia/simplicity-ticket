@if(! Configuration::smtp())
<div class="alert alert-warning">
	<i class="fa fa-icon fa-exclamation-triangle"></i>
	You do not have SMTP environment variables configured. The ability to send emails can't be enabled until this has been configured.
</div>
@endif
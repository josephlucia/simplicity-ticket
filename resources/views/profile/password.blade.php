@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="account-title">Account Settings</div>
	<div class="row">
		<div class="col-md-3">
			@include('profile.partials.nav')
		</div>
		<div class="col-md-7">
		<form action="{{ url('/password') }}" method="POST">
		{{ csrf_field() }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-component-heading">
						Change Password
					</div>
				</div>
				<div class="panel-body">

	                @include('common.alerts')

					<div class="form-group">
						<label>Current Password:</label>
						<input type="password" class="form-control" name="current_password">
					</div>
					<div class="form-group">
						<label>New Password:</label>
						<input type="password" class="form-control" name="password">
					</div>
					<div class="form-group">
						<label>Confirm New Password:</label>
						<input type="password" class="form-control" name="password_confirmation">
					</div>
					<button type="submit" class="btn btn-primary">Save Password</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
@endsection

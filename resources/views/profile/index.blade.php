@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="account-title">Account Settings</div>
	<div class="row">
		<div class="col-md-3">
			@include('profile.partials.nav')
		</div>
		<div class="col-md-7">
			<form action="{{ url('/profile') }}" method="POST">
			{{ csrf_field() }}
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-component-heading">
							Profile
						</div>
					</div>
					<div class="panel-body">

						@include('common.alerts')

						<div class="form-group">
							<label>Name:</label>
							<input type="text" class="form-control" name="name" value="{{ $user->name }}">
						</div>
						<div class="form-group">
							<label>Email:</label>
							<input type="text" class="form-control" name="email" value="{{ $user->email }}">
						</div>
						<button type="submit" class="btn btn-primary">Save Profile</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <sts-settings-nav></sts-settings-nav>
                </div>
                <div class="panel-body">

                    <div class="panel-box">
                        <p>
                            This management panel allows you to bulk transfer tickets from users and departments.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Bulk Staff Ticket Transfer
                                </div>
                                <div class="panel-body">
                                    <form action="{{ url('/settings/transfer/staff') }}" method="POST" class="form-horizontal" role="form">
                                    {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">From:</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="from">
                                                    <option value="">Please Select</option>
                                                    @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">To:</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="to">
                                                    <option value="">Please Select</option>
                                                    @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-9">
                                                <button type="submit" class="btn btn-primary">Transfer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Bulk Department Ticket Transfer
                                </div>
                                <div class="panel-body">
                                    <form action="{{ url('/settings/transfer/departments') }}" method="POST" class="form-horizontal" role="form">
                                    {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">From:</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="from">
                                                    <option value="">Please Select</option>
                                                    @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">To:</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="to">
                                                    <option value="">Please Select</option>
                                                    @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-9">
                                                <button type="submit" class="btn btn-primary">Transfer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

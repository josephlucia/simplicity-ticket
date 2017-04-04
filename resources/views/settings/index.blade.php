@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            
            @include('settings.partials.smtp')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <sts-settings-nav></sts-settings-nav>
                </div>
                <div class="panel-body">
                    <div class="panel-box">
                        <p>
                            This management panel allows you to manage your organizational settings. You may customize the following settings.
                            <ul>
                                <li>Update the application name to something that fits your organizations branding.</li>
                                <li>Don't have departments? Substitute <b>Department</b> for a more suitable name. Ex: Entity, Building, School, etc.</li>
                            </ul>
                        </p>
                    </div>
                    <form action="{{ url('/settings/organization') }}" method="POST">
                    {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-label">Application Name</div>
                            <div class="form-input"><input type="text" class="form-control" name="application_name" value="{{ Configuration::value('application_name') }}"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">Department Alias</div>
                            <div class="form-input"><input type="text" class="form-control" name="department_alias" value="{{ Configuration::value('department_alias') }}"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">Organization Name</div>
                            <div class="form-input"><input type="text" class="form-control" name="organization_name" value="{{ Configuration::value('organization_name') }}"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">Send Emails</div>
                            <div class="form-input">
                                <select class="form-control" name="send_email">
                                    <option value="yes" {{ Configuration::value('send_email') == 'yes' ? 'selected' : '' }}>Enabled</option>
                                    <option value="" {{ Configuration::value('send_email') == '' ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

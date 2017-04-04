<?php

namespace App\Http\Controllers\Settings;

use App\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Update the specified organization details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Set the configuration keys.
    	$keys = ['application_name', 'department_alias', 'organization_name', 'send_email'];
        // Loop through configuration keys to set the values.
    	foreach($keys as $key) {
            $config = Configuration::set($key, $request->$key);
    	}

        return back();
    }
}

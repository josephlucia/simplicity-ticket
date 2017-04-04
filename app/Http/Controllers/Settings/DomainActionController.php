<?php

namespace App\Http\Controllers\Settings;

use App\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DomainActionController extends Controller
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
     * Display the specified domain type.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Get the saved action.
        $config = Configuration::where('key', 'domain')->first();
        $action = is_null($config) ? 'blacklist' : $config->value;

        return ucfirst($action);
    }

    /**
     * Update the specified domain type.
     *
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function update($type)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Reverse the domain filtering type.
        $reverse = ($type == 'Blacklist') ? 'whitelist' : 'blacklist';
        // Get the setting value if it exists.
        $config = Configuration::where('key', 'domain')->first();
        // Create or update the value.
        if(is_null($config)) {
            Configuration::create([
                'key' => 'domain',
                'value' => $reverse
            ]);
        } else {
        	$config->update(['value' => $reverse]);
        }
    }
}

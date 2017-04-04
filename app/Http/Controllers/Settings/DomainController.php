<?php

namespace App\Http\Controllers\Settings;

use App\Domain;
use App\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DomainController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.domains.index');
    }

    /**
     * Display all of domains.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        // Get all domains
        $domains = Domain::orderBy('name', 'asc')->get();

        return $domains;
    }

    /**
     * Store a new domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
            'name' => 'required',
        ]);
        // Create the domain.
        $domain = Domain::create([
            'name' => $request->name
        ]);
    }

    /**
     * Update the specified domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
            'name' => 'required',
        ]);
        // Find the domain by id.
        $domain = Domain::findOrFail($id);
        // Update the domain name.
        $domain->update(['name' => $request->name]);
    }

    /**
     * Remove the specified domain.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Find the domain by id.
        $domain = Domain::findOrFail($id); 
        // Delete the domain.
        $domain->delete();
    }
}

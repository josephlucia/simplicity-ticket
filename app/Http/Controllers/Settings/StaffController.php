<?php

namespace App\Http\Controllers\Settings;

use App\User;
use Validator;
use Exception;
use App\Ticket;
use App\Configuration;
use App\DepartmentUser;
use App\Mail\WelcomeStaff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;



class StaffController extends Controller
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
        return view('settings.staff.index');
    }

    /**
     * Display all staff members.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Get all staff.
        $staff = User::with('departments')
                     ->where('role', '!=', 'user')
        			 ->orderBy('role', 'asc')
        			 ->orderBy('name', 'asc')
        			 ->get();

        return $staff;
    }

    /**
     * Display a random password.
     *
     * @return string
     */
    public function password()
    {
        return str_random(12);
    }

    /**
     * Store a new staff member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
        	'role' => 'required',
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|min:6',
        ]);
        // Create the staff member.
        $staff = User::create([
        	'role' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        // Send welcome email.
        if(! is_null(Configuration::value('send_email'))) {
            try {
                Mail::to($staff)->send(new WelcomeStaff($staff, $request->password));
            } catch (Exception $exception) {
                // Display the mail not send error.
                return response()->json([
                    'errors' => 'Account successfully created, but the email failed. Error: ' . $exception->getMessage() . '. Please check your .env settings.'
                ], 422);
            }
        }
    }

    /**
     * Update the specified staff member.
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
        	'role' => 'required',
            'name' => 'required|max:191',
            'email' => ['required', 'email', 'max:191', Rule::unique('users')->ignore($id)]
        ]);
        // Find the user by id.
        $user = User::findOrFail($id);
        // Update the user.
        $user->update([
        	'role' => $request->role,
        	'name' => $request->name,
        	'email' => $request->email
        ]);
    }

    /**
     * Remove the specified staff member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Find the user by id.
        $user = User::findOrFail($id);
        // Remove all associated departments.
        $departments = DepartmentUser::where('user_id', $user->id)->delete();
        // Nullify any tickets assigned to the user.
        $tickets = Ticket::where('assigned_to', $user->id)->update(['assigned_to' => null]);
        // Delete the user.
        $user->delete();
    }
}

<?php

namespace App\Http\Controllers\Settings;

use App\Ticket;
use App\Department;
use App\DepartmentUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
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
        return view('settings.departments.index');
    }

    /**
     * Display all departments.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        // Get all departments
        $departments = Department::orderBy('name', 'asc')->get();

        return $departments;
    }

    /**
     * Store a new department.
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
        // Create the department.
        $department = Department::create([
            'name' => $request->name
        ]);
    }

    /**
     * Update the specified department.
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
        // Find the department by id.
        $department = Department::findOrFail($id);
        // Update the department name.
        $department->update(['name' => $request->name]);
    }

    /**
     * Remove the specified department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Find the department by id.
        $department = Department::findOrFail($id); 
        // Check if tickets are associated to this department.
        $tickets = Ticket::where('department_id', $department->id)->count();
        // Delete the department if allowed.
        if($tickets == 0) {
            $departments = Department::count();
            if($departments > 1) {
                // Delete the department.
                $department->delete();
                // Delete any department user associations.
                $departments = DepartmentUser::where('department_id', $department->id)->delete();
            } else {
                // Cancel the deletion and return error.
                return response()->json([
                    'errors' => 'You may not delete this entity if it is the last record. Please add a new record or edit this one.'
                ], 422);
            }
        } else {
            // Cancel the deletion and return error.
            return response()->json([
                'errors' => 'You may not delete this entity until there are no tickets associated. Please use the bulk transfer tool to complete and try again.'
            ], 422);
        }
    }
}

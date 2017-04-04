<?php

namespace App\Http\Controllers\Settings;

use App\User;
use App\Department;
use App\DepartmentUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffDepartmentController extends Controller
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
     * Display all associated departments.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function associated($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Get all staff departments
        $user = User::find($id);

        return $user->departments()->orderBy('name')->get();
    }

    /**
     * Display all available departments.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function available($id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Find user by id.
        $user = User::find($id);
        // Get user department id's.
        $userDepartments = $user->departments()->get()->pluck('id');
        // Get all departments.
        $departments = Department::orderBy('name', 'asc')->get();

        return $departments->whereNotIn('id', $userDepartments);
    }

    /**
     * Store a new department to the staff.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Validate the request.
        $this->validate($request, [
            'department' => 'required'
        ]);
        // Create the department.
        $department = DepartmentUser::create([
            'department_id' => $request->department,
            'user_id' => $id
        ]);
    }

    /**
     * Remove the specified department from the user.
     *
     * @param  int  $id
     * @param  int  $deptId
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $deptId)
    {
        abort_unless(auth()->user()->role === 'admin', 404);
        // Delete the department from the user.
        DepartmentUser::where('user_id', $id)->where('department_id', $deptId)->delete();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User; 
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Fetch search and filter query strings
        $search = $request->input('search');
        $departmentId = $request->input('department_id');
        $status = $request->input('status');

        // 2. Build query
        $query = Employee::with('department');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $employees = $query->latest()->paginate(10);

        // 3. Interface metrics
        $metrics = [
            'total'          => Employee::count(),
            'active'         => Employee::where('status', 'Active')->count(),
            'inactive'       => Employee::where('status', 'Inactive')->count(),
            'new_this_month' => Employee::whereMonth('joining_date', date('m'))
                                        ->whereYear('joining_date', date('Y'))
                                        ->count(),
        ];

        $departments = Department::all();

        return view('hrm.employees.index', compact('employees', 'metrics', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email|unique:employees,email',
            'phone'             => 'required|string|max:20',
            'username'          => 'required|string|max:50',
            'password'          => 'required|string|min:6',
            'role'              => 'required|string|in:Admin,Manager,Employee',
            'gender'            => 'required|string',
            'dob'               => 'required|date',
            'emergency_contact' => 'required|string',
            'address'           => 'required|string',
            'designation'       => 'required|string',
            'department_id'     => 'required|exists:departments,id',
            'emp_type'          => 'required|string',
            'salary'            => 'required|numeric|min:0',
            'joining_date'      => 'required|date',
            'status'            => 'required|string|in:Active,Inactive',
        ]);

        DB::beginTransaction();

        try {
            $username = $request->input('username') ?? explode('@', $request->email)[0];

            // 1. Generate user authentication profile
            $user = User::create([
                'name'     => $request->first_name . ' ' . $request->last_name,
                'email'    => $request->email,
                'username' => $username,
                'password' => Hash::make($request->password),
                'role'     => $request->role, 
            ]);

            // 2. Save employee record
            $employee = new Employee();
            
            // Assign user_id dynamically if column exists in table schema
            if (Schema::hasColumn('employees', 'user_id')) {
                $employee->user_id = $user->id;
            }

            // Explicitly set non-nullable table attributes
            $employee->username = $username;

            if (Schema::hasColumn('employees', 'password')) {
                $employee->password = Hash::make($request->password);
            }

            $employee->first_name        = $validatedData['first_name'];
            $employee->last_name         = $validatedData['last_name'];
            $employee->email             = $validatedData['email'];
            $employee->phone             = $validatedData['phone'];
            $employee->gender            = $validatedData['gender'];
            $employee->dob               = $validatedData['dob'];
            $employee->emergency_contact = $validatedData['emergency_contact'];
            $employee->address           = $validatedData['address'];
            $employee->designation       = $validatedData['designation'];
            $employee->department_id     = $validatedData['department_id'];
            $employee->emp_type          = $validatedData['emp_type'];
            $employee->salary            = $validatedData['salary'];
            $employee->joining_date      = $validatedData['joining_date'];
            $employee->status            = $validatedData['status'];
            
            // employee_code is generated automatically via Employee model boot() method
            
            $employee->save();

            DB::commit();

            return redirect()->route('hrm.employees.index')
                             ->with('success', 'Workforce entity securely deployed and login access generated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Critical error saving records: ' . $e->getMessage()]);
        }
    }
}
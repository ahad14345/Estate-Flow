<?php

namespace App\Http\Controllers;

use App\Models\{Employee, Department};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller {
    public function index(Request $request) {
        $query = Employee::with('department');

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(employee_code) LIKE ?', ["%{$search}%"]);
            });
        }
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);
        if ($request->filled('status')) $query->where('status', $request->status);

        $employees = $query->paginate(10)->withQueryString();
        $departments = Department::where('status', 'Active')->get();

        $metrics = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'Active')->count(),
            'inactive' => Employee::where('status', 'Inactive')->count(),
            'new_this_month' => Employee::whereBetween('joining_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count(),
        ];

        return view('hrm.employees.index', compact('employees', 'departments', 'metrics'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string',
            'gender' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'emp_type' => 'required',
            'salary' => 'required|numeric',
            'emergency_contact' => 'required',
            'username' => 'required|unique:employees,username',
            'password' => 'required|min:6',
            'status' => 'required'
        ]);

        $data['password'] = Hash::make($data['password']);
        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee deployed successfully.');
    }

    public function update(Request $request, Employee $employee) {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string',
            'status' => 'required',
            'salary' => 'required|numeric'
        ]);
        
        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Profile parameters modified successfully.');
    }

    public function destroy(Employee $employee) {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee record archived.');
    }

    public function exportCSV() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="employees_report.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Code', 'Full Name', 'Email', 'Designation', 'Status']);
        foreach(Employee::all() as $emp) {
            fputcsv($output, [$emp->employee_code, $emp->first_name.' '.$emp->last_name, $emp->email, $emp->designation, $emp->status]);
        }
        fclose($output);
        exit;
    }
}
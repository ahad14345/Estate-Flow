<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller {
    public function index() {
        $departments = Department::withCount('employees')->paginate(10);
        
        $largest = Department::withCount('employees')->orderBy('employees_count', 'desc')->first();
        
        $metrics = [
            'total' => Department::count(),
            'total_emp' => \App\Models\Employee::count(),
            'largest' => $largest ? $largest->name : 'N/A'
        ];

        return view('hrm.departments.index', compact('departments', 'metrics'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|unique:departments,name',
            'dept_head' => 'nullable|string',
            'status' => 'required'
        ]);
        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Department established.');
    }

    public function update(Request $request, Department $department) {
        $data = $request->validate([
            'name' => 'required|string|unique:departments,name,'.$department->id,
            'dept_head' => 'nullable|string',
            'status' => 'required'
        ]);
        $department->update($data);
        return redirect()->route('departments.index')->with('success', 'Department structural values updated.');
    }

    public function destroy(Department $department) {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department archived.');
    }
}
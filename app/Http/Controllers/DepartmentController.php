<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->get();

        $metrics = [
            'total'     => Department::count(),
            'total_emp' => Employee::count(), 
            'largest'   => Department::withCount('employees')
                            ->orderBy('employees_count', 'desc')
                            ->first()?->name ?? 'None'
        ];

        return view('hrm.departments.index', compact('departments', 'metrics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dept_head' => 'nullable|string|max:255',
        ]);

        Department::create($validated);

        return redirect()->route('hrm.departments.index')
            ->with('success', 'Department structural node provisioned successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete(); // Automatically utilizes SoftDeletes safely

        return redirect()->route('hrm.departments.index')
            ->with('success', 'Department structural node archived.');
    }
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name'      => 'required|string|max:255',
        'dept_head' => 'nullable|string|max:255',
        'status'    => 'required|string|in:Active,Inactive,Suspended',
    ]);

    $department = Department::findOrFail($id);
    $department->update($validated);

    return redirect()->route('hrm.departments.index')
        ->with('success', 'Department operational profile modified.');
}
}
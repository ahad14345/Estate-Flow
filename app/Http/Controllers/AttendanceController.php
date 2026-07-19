<?php

namespace App\Http\Controllers;

use App\Models\{Attendance, Employee, Department};
use Illuminate\Http\Request;

class AttendanceController extends Controller {
    public function index(Request $request) {
        $query = Attendance::with('employee.department')->whereDate('attendance_date', $request->input('date', today()));

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $attendances = $query->paginate(15);
        $departments = Department::all();
        $employees = Employee::where('status', 'Active')->get();

        $totalActive = Employee::where('status', 'Active')->count();
        $present = Attendance::whereDate('attendance_date', today())->whereIn('status', ['Present', 'Late'])->count();
        $late = Attendance::whereDate('attendance_date', today())->where('status', 'Late')->count();
        
        $metrics = [
            'present' => $present,
            'absent' => max(0, $totalActive - $present),
            'late' => $late,
            'rate' => $totalActive > 0 ? round(($present / $totalActive) * 100, 1) : 0
        ];

        return view('hrm.attendance.index', compact('attendances', 'departments', 'employees', 'metrics'));
    }

    public function mark(Request $request) {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:Present,Absent,Late'
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'attendance_date' => $data['attendance_date']],
            $data
        );

        return redirect()->route('attendance.index')->with('success', 'Attendance tracking synced.');
    }

    public function exportCSV() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance_logs.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Employee ID', 'Date', 'Check In', 'Check Out', 'Status']);
        foreach(Attendance::with('employee')->get() as $att) {
            fputcsv($output, [$att->employee->employee_code, $att->attendance_date, $att->check_in, $att->check_out, $att->status]);
        }
        fclose($output);
        exit;
    }
}
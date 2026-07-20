@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Operational Shift Attendance Logs</h4>
            <p class="text-muted small mb-0">Real-time verification metrics, time tracking checkpoints, and daily performance sync indicators.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('hrm.attendance.export') }}" class="btn btn-outline-success rounded-3 small text-xs"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Logs</a>
            <button class="btn btn-dark px-4 py-2 rounded-3 fw-medium text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                <i class="bi bi-clock me-2"></i>Log Check-In
            </button>
        </div>
    </div>

    <!-- KPI Matrix Stripe -->
    <div class="row g-3 mb-4">
        @foreach([['Present Shifts', $metrics['present'], 'bg-success text-success'], ['Absent Displacements', $metrics['absent'], 'bg-danger text-danger'], ['Late Flag Warnings', $metrics['late'], 'bg-warning text-warning'], ['Sync Yield Ratio', $metrics['rate'].'%', 'bg-primary text-primary']] as $kpi)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted text-xs font-monospace text-uppercase">{{ $kpi[0] }}</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $kpi[1] }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Processing Data Log Grid -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light font-monospace text-muted small">
                    <tr>
                        <th class="ps-4">Employee Resource</th>
                        <th>Target Operational Department</th>
                        <th>Calendar Date</th>
                        <th>Check In Point</th>
                        <th>Check Out Point</th>
                        <th class="pe-4">Verification Status</th>
                    </tr>
                </thead>
                <tbody class="small text-dark">
                    @forelse($attendances as $att)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $att->employee->first_name }} {{ $att->employee->last_name }}</td>
                        <td>{{ $att->employee->department->name }}</td>
                        <td class="font-monospace">{{ $att->attendance_date }}</td>
                        <td class="font-monospace text-success">{{ $att->check_in ?? '--:--' }}</td>
                        <td class="font-monospace text-secondary">{{ $att->check_out ?? '--:--' }}</td>
                        <td class="pe-4">
                            <span class="badge px-2.5 py-1 rounded-pill {{ $att->status === 'Present' ? 'bg-success bg-opacity-10 text-success' : ($att->status === 'Late' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-danger bg-opacity-10 text-danger') }}">
                                {{ $att->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No systemic operational logs synchronized for this index timestamp.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
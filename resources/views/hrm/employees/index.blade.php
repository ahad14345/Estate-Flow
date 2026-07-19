@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Employee Portfolio Matrix</h4>
            <p class="text-muted small mb-0">Control structural workforce entities, profiles, deployment metrics, and baseline values.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.export') }}" class="btn btn-outline-success rounded-3 small text-xs"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV</a>
            <button class="btn btn-primary px-4 py-2 rounded-3 text-white shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#deployEmployeeModal">
                <i class="bi bi-person-plus me-2"></i>Deploy Employee
            </button>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="row g-3 mb-4">
        @foreach([['Total Workgroup', $metrics['total'], 'bi-people text-primary bg-primary'], ['Active Deployments', $metrics['active'], 'bi-patch-check text-success bg-success'], ['Archived Status', $metrics['inactive'], 'bi-person-x text-danger bg-danger'], ['Joined This Month', $metrics['new_this_month'], 'bi-calendar-plus text-info bg-info']] as $metric)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-monospace text-xs">{{ $metric[0] }}</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $metric[1] }}</h3>
                    </div>
                    <div class="{{ $metric[2] }} bg-opacity-10 p-2.5 rounded-3 fs-4"><i class="bi {{ explode(' ', $metric[2])[0] }}"></i></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Active Filter Strip -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-0 px-3" placeholder="Search full name, systemic designatory fields, serial index code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select form-select-sm bg-light border-0">
                        <option value="">Filter By Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light border-0">
                        <option value="">Status Index</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-medium">Execute Lookup</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Core Interactive Data Matrix -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light font-monospace text-muted small">
                    <tr>
                        <th class="ps-4">Identity Parameters</th>
                        <th>Organizational Element</th>
                        <th>Communication Endpoint</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">System Actions</th>
                    </tr>
                </thead>
                <tbody class="small text-dark">
                    @forelse($employees as $emp)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar bg-light border text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px; height:38px;">
                                    {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                    <span class="text-xs text-muted font-monospace">{{ $emp->employee_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $emp->department->name }}</div>
                            <span class="text-xs text-muted">{{ $emp->designation }}</span>
                        </td>
                        <td>
                            <div>{{ $emp->email }}</div>
                            <span class="text-xs text-muted font-monospace">{{ $emp->phone }}</span>
                        </td>
                        <td class="font-monospace">{{ $emp->joining_date->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge rounded-pill px-2.5 py-1 {{ $emp->status === 'Active' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                {{ $emp->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1.5">
                                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{$emp->id}}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive record?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No systemic employee entities registered.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
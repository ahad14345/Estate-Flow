@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Organizational Departments</h4>
            <p class="text-muted small mb-0">Construct and modify baseline structural corporate operational nodes.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-3 text-white shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <i class="bi bi-plus-lg me-2"></i>Create Department
        </button>
    </div>

    <!-- Active Registry Grid -->
    <div class="row g-3 mb-4">
        @foreach([['Departments Block', $metrics['total'], 'bi-diagram-3 text-primary'], ['Total Assigned Workforce', $metrics['total_emp'], 'bi-people text-success'], ['Largest Node Cluster', $metrics['largest'], 'bi-building text-warning']] as $card)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted text-xs font-monospace text-uppercase">{{ $card[0] }}</span>
                <h4 class="fw-bold text-dark mt-2 mb-0">{{ $card[1] }}</h4>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light font-monospace text-muted small">
                    <tr>
                        <th class="ps-4" width="80">Index ID</th>
                        <th>Department Structural Parameter</th>
                        <th>Assigned Unit Lead</th>
                        <th>Workforce Headcount</th>
                        <th>Status</th>
                        <th class="text-end pe-4" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody class="small text-dark">
                    @foreach($departments as $dept)
                    <tr>
                        <td class="ps-4 font-monospace fw-semibold">#{{ $dept->id }}</td>
                        <td class="fw-bold text-primary">{{ $dept->name }}</td>
                        <td><i class="bi bi-person-badge me-2 text-muted"></i>{{ $dept->dept_head ?? 'Unassigned' }}</td>
                        <td class="font-monospace fw-medium">{{ $dept->employees_count }} Units</td>
                        <td><span class="badge px-2.5 py-1 rounded-pill bg-success bg-opacity-10 text-success">{{ $dept->status }}</span></td>
                        <td class="text-end pe-4">
                            <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onsubmit="return confirm('Archive parameter?');"><i class="bi bi-trash3"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
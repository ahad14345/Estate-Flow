@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <!-- Top Action Ribbon -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Organizational Departments</h4>
            <p class="text-muted small mb-0">Construct and modify baseline structural corporate operational nodes.</p>
        </div>
        <!-- Bootstrap 5 Trigger Target Module -->
        <button class="btn btn-primary px-4 py-2 rounded-3 text-white shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <i class="bi bi-plus-lg me-2"></i>Create Department
        </button>
    </div>

    <!-- Active Registry Metric Grid Cards -->
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

    <!-- Core Interactive Registry Layout -->
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
                        <th class="text-end pe-4" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody class="small text-dark">
                    @forelse($departments as $dept)
                    <tr>
                        <td class="ps-4 font-monospace fw-semibold">#{{ $dept->id }}</td>
                        <td class="fw-bold text-primary">{{ $dept->name }}</td>
                        <td><i class="bi bi-person-badge me-2 text-muted"></i>{{ $dept->dept_head ?? 'Unassigned' }}</td>
                        <td class="font-monospace fw-medium">{{ $dept->employees_count ?? 0 }} Units</td>
                        <td>
                            @php
                                $statusClass = match($dept->status ?? 'Active') {
                                    'Active' => 'bg-success text-success',
                                    'Suspended' => 'bg-warning text-warning',
                                    default => 'bg-danger text-danger',
                                };
                            @endphp
                            <span class="badge px-2.5 py-1 rounded-pill bg-opacity-10 {{ $statusClass }}">
                                {{ $dept->status ?? 'Active' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <!-- Trigger Edit Modal -->
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editDeptModal{{ $dept->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <!-- Destroy Form Scope Anchor -->
                                <form action="{{ route('hrm.departments.destroy', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive department parameter?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Dynamic Row Mutating Modification Modal -->
                    <div class="modal fade" id="editDeptModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-3 text-start">
                                <div class="modal-header bg-light border-0 py-3">
                                    <h5 class="modal-title fw-bold text-dark small font-monospace text-uppercase">Modify Department Node</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('hrm.departments.update', $dept->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label font-monospace text-muted small">Department Structural Name</label>
                                            <input type="text" class="form-control rounded-2 py-2" name="name" value="{{ $dept->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label font-monospace text-muted small">Assigned Unit Lead</label>
                                            <input type="text" class="form-control rounded-2 py-2" name="dept_head" value="{{ $dept->dept_head }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label font-monospace text-muted small">Operational Status Index</label>
                                            <select class="form-select rounded-2 py-2" name="status" required>
                                                <option value="Active" {{ ($dept->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ ($dept->status ?? 'Active') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="Suspended" {{ ($dept->status ?? 'Active') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 bg-light py-2">
                                        <button type="button" class="btn btn-link text-muted font-monospace text-decoration-none small" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 text-white font-monospace small">Update Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4 font-monospace">No organizational structural nodes registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Department Modal (Appended Directly Into DOM Layout) -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-labelledby="addDeptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark small font-monospace text-uppercase" id="addDeptModalLabel">Create Department Node</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hrm.departments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label font-monospace text-muted small">Department Structural Name</label>
                        <input type="text" class="form-control rounded-2 py-2" id="name" name="name" placeholder="e.g., Structural Engineering" required>
                    </div>
                    <div class="mb-3">
                        <label for="dept_head" class="form-label font-monospace text-muted small">Assigned Unit Lead (Optional)</label>
                        <input type="text" class="form-control rounded-2 py-2" id="dept_head" name="dept_head" placeholder="e.g., Mirza Admin Node">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2">
                    <button type="button" class="btn btn-link text-muted font-monospace text-decoration-none small" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 text-white font-monospace small">Save Parameter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
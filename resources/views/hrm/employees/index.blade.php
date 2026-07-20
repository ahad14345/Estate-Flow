@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Employee Portfolio Matrix</h4>
            <p class="text-muted small mb-0">Control structural workforce entities, profiles, deployment metrics, and baseline values.</p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->hasModuleAccess('hrm'))
                <a href="{{ route('hrm.employees.export', request()->query()) }}" class="btn btn-outline-success rounded-3 small text-xs d-flex align-items-center">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </a>
                <button class="btn btn-primary px-4 py-2 rounded-3 text-white shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#deployEmployeeModal">
                    <i class="bi bi-person-plus me-2"></i>Deploy Employee
                </button>
            @endif
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
                    <div class="{{ explode(' ', $metric[2])[1] }} bg-opacity-10 p-2.5 rounded-3 fs-4">
                        <i class="bi {{ explode(' ', $metric[2])[0] }} {{ explode(' ', $metric[2])[1] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Active Filter Strip -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('hrm.employees.index') }}" method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-0 px-3 py-2" placeholder="Search full name, systemic designatory fields, serial index code..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select form-select-sm bg-light border-0 py-2">
                        <option value="">Filter By Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light border-0 py-2">
                        <option value="">Status Index</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-medium">Execute Lookup</button>
                    <a href="{{ route('hrm.employees.index') }}" class="btn btn-sm btn-light border d-flex align-items-center justify-content-center"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                        @if(auth()->user()->hasModuleAccess('hrm'))
                            <th class="text-end pe-4">System Actions</th>
                        @endif
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
                            <div class="fw-semibold text-dark">{{ $emp->department->name ?? 'Unassigned Node' }}</div>
                            <span class="text-xs text-muted">{{ $emp->designation }}</span>
                        </td>
                        <td>
                            <div>{{ $emp->email }}</div>
                            <span class="text-xs text-muted font-monospace">{{ $emp->phone }}</span>
                        </td>
                        <td class="font-monospace">
                            {{ $emp->joining_date ? ($emp->joining_date instanceof \Carbon\Carbon ? $emp->joining_date->format('Y-m-d') : date('Y-m-d', strtotime($emp->joining_date))) : 'N/A' }}
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2.5 py-1 {{ $emp->status === 'Active' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                {{ $emp->status }}
                            </span>
                        </td>
                        @if(auth()->user()->hasModuleAccess('hrm'))
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{$emp->id}}"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('hrm.employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive record?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>

                    @if(auth()->user()->hasModuleAccess('hrm'))
                        <!-- Dynamic Structural Edit Modal Sub-Block -->
                        <div class="modal fade" id="editModal{{$emp->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-3 text-start">
                                    <div class="modal-header bg-light border-0 py-3">
                                        <h5 class="modal-title fw-bold text-dark small font-monospace text-uppercase">Modify Employee Record</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('hrm.employees.update', $emp->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label class="form-label font-monospace text-muted small">First Name</label>
                                                    <input type="text" class="form-control rounded-2" name="first_name" value="{{ $emp->first_name }}" required>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label font-monospace text-muted small">Last Name</label>
                                                    <input type="text" class="form-control rounded-2" name="last_name" value="{{ $emp->last_name }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-monospace text-muted small">Email Address</label>
                                                <input type="email" class="form-control rounded-2" name="email" value="{{ $emp->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-monospace text-muted small">Phone Target</label>
                                                <input type="text" class="form-control rounded-2" name="phone" value="{{ $emp->phone }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-monospace text-muted small">Designation Text</label>
                                                <input type="text" class="form-control rounded-2" name="designation" value="{{ $emp->designation }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-monospace text-muted small">Department Node Mapping</label>
                                                <select class="form-select rounded-2" name="department_id" required>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ $emp->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-monospace text-muted small">Deployment Lifecycle Status</label>
                                                <select class="form-select rounded-2" name="status" required>
                                                    <option value="Active" {{ $emp->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ $emp->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light py-2">
                                            <button type="button" class="btn btn-link text-muted font-monospace text-decoration-none small" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 text-white font-monospace small">Apply System Mutations</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasModuleAccess('hrm') ? '6' : '5' }}" class="text-center py-5 text-muted font-monospace">
                            No systemic employee entities registered.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(auth()->user()->hasModuleAccess('hrm'))
    <!-- Structural Deployment Primary Modal Block -->
    <div class="modal fade @if($errors->any()) show @endif" id="deployEmployeeModal" tabindex="-1" aria-labelledby="deployEmployeeModalLabel" @if($errors->any()) style="display: block;" aria-modal="true" role="dialog" @else aria-hidden="true" @endif>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-3 text-start">
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark small font-monospace text-uppercase" id="deployEmployeeModalLabel">Deploy System Workforce Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('hrm.employees.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        
                        <!-- Section 1: Core Identity -->
                        <div class="text-muted font-monospace small mb-2 text-uppercase fw-bold border-bottom pb-1 text-primary">1. Core Identity Matrix</div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label font-monospace text-muted small mb-1">First Name</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="John" required>
                                @error('first_name') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label font-monospace text-muted small mb-1">Last Name</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required>
                                @error('last_name') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label font-monospace text-muted small mb-1">Corporate Email Address</label>
                                <input type="email" class="form-control form-control-sm rounded-2 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="username@estateflow.com" required>
                                @error('email') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label font-monospace text-muted small mb-1">Phone Contact String</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+8801XXXXXXXXX" required>
                                @error('phone') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Section 2: Account Access & Authentication -->
                        <div class="text-muted font-monospace small mb-2 text-uppercase fw-bold border-bottom pb-1 text-primary mt-4">2. Authentication Matrix</div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label for="username" class="form-label font-monospace text-muted small mb-1">System Username</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="johndoe12" required>
                                @error('username') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="password" class="form-label font-monospace text-muted small mb-1">Account Password</label>
                                <input type="password" class="form-control form-control-sm rounded-2 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                                @error('password') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="role" class="form-label font-monospace text-muted small mb-1">Access Authorization Role</label>
                                <select class="form-select form-select-sm rounded-2 @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Access Node...</option>
                                    <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin (Global Unrestricted Access)</option>
                                    <option value="Employee" {{ old('role') === 'Employee' ? 'selected' : '' }}>Employee (Module Permitted Access)</option>
                                </select>
                                @error('role') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Section 3: Personal Profiles -->
                        <div class="text-muted font-monospace small mb-2 text-uppercase fw-bold border-bottom pb-1 text-primary mt-4">3. Demographics & Parameters</div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label for="gender" class="form-label font-monospace text-muted small mb-1">Gender Vector</label>
                                <select class="form-select form-select-sm rounded-2 @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender...</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label font-monospace text-muted small mb-1">Date of Birth</label>
                                <input type="date" class="form-control form-control-sm rounded-2 @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob') }}" required>
                                @error('dob') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="emergency_contact" class="form-label font-monospace text-muted small mb-1">Emergency Contact Target</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('emergency_contact') is-invalid @enderror" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Contact Number" required>
                                @error('emergency_contact') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label font-monospace text-muted small mb-1">Physical Residential Address</label>
                            <textarea class="form-control form-control-sm rounded-2 @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Street layout details..." required>{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                        </div>

                        <!-- Section 4: Employment Framework -->
                        <div class="text-muted font-monospace small mb-2 text-uppercase fw-bold border-bottom pb-1 text-primary mt-4">4. Deployment Strategy</div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="designation" class="form-label font-monospace text-muted small mb-1">Corporate Designation Structural Field</label>
                                <input type="text" class="form-control form-control-sm rounded-2 @error('designation') is-invalid @enderror" id="designation" name="designation" value="{{ old('designation') }}" placeholder="e.g., Lead Site Surveyor" required>
                                @error('designation') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="department_id" class="form-label font-monospace text-muted small mb-1">Assign Structural Department Node</label>
                                <select class="form-select form-select-sm rounded-2 @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                    <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>Select Allocation Node Cluster...</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="emp_type" class="form-label font-monospace text-muted small mb-1">Employment Metric</label>
                                <select class="form-select form-select-sm rounded-2 @error('emp_type') is-invalid @enderror" id="emp_type" name="emp_type" required>
                                    <option value="" disabled {{ old('emp_type') ? '' : 'selected' }}>Select Type...</option>
                                    <option value="Full-Time" {{ old('emp_type') === 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="Part-Time" {{ old('emp_type') === 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="Contractual" {{ old('emp_type') === 'Contractual' ? 'selected' : '' }}>Contractual</option>
                                </select>
                                @error('emp_type') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="salary" class="form-label font-monospace text-muted small mb-1">Base Salary (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm rounded-2 @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary') }}" placeholder="50000" required>
                                @error('salary') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="joining_date" class="form-label font-monospace text-muted small mb-1">Activation Date</label>
                                <input type="date" class="form-control form-control-sm rounded-2 @error('joining_date') is-invalid @enderror" id="joining_date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" required>
                                @error('joining_date') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label font-monospace text-muted small mb-1">Lifecycle Status</label>
                                <select class="form-select form-select-sm rounded-2 @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Active" {{ old('status') === 'Active' ? 'selected' : 'default' }}>Active</option>
                                    <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 bg-light py-2">
                        <button type="button" class="btn btn-link text-muted font-monospace text-decoration-none small" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-2 text-white font-monospace small">Commit Asset Deployment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="modal-backdrop fade show"></div>
    @endif
@endif
@endsection
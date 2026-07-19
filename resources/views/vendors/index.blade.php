@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3 bg-light min-vh-100">
    <!-- Breadcrumb Actions Layout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Vendor Management</h4>
            <p class="text-muted small mb-0">Manage corporate supplier relationships, financial analytics, and procurement records.</p>
        </div>
        <div>
            <button class="btn btn-primary px-4 py-2 rounded-3 fw-medium text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                <i class="bi bi-plus-lg me-2"></i>Register Vendor
            </button>
        </div>
    </div>

    <!-- Analytics Dashboard Cards Matrix -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-monospace small">Total Suppliers</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $metrics['total'] }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2.5 rounded-3 text-primary fs-4"><i class="bi bi-building"></i></div>
                </div>
                <div class="mt-2.5 text-muted small"><span class="text-success font-medium"><i class="bi bi-arrow-up-short"></i>{{ $metrics['new_this_month'] }}</span> this month</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-monospace small">Active Operations</span>
                        <h3 class="fw-bold text-success mt-1 mb-0">{{ $metrics['active'] }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2.5 rounded-3 text-success fs-4"><i class="bi bi-patch-check"></i></div>
                </div>
                <div class="mt-2.5 text-muted small">Inactive: <span class="text-danger fw-bold">{{ $metrics['inactive'] }}</span> units</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-monospace small">Procurement Turnover</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">৳{{ number_format($metrics['total_value'], 0) }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-2.5 rounded-3 text-info fs-4"><i class="bi bi-currency-bangladeshi"></i></div>
                </div>
                <div class="mt-2.5 text-muted small">Via <span class="fw-medium text-dark">{{ $metrics['total_pos'] }}</span> Purchase Orders</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase font-monospace small">Outstanding Liabilities</span>
                        <h3 class="fw-bold text-danger mt-1 mb-0">৳{{ number_format($metrics['pending_payment'], 0) }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2.5 rounded-3 text-danger fs-4"><i class="bi bi-credit-card-2-back"></i></div>
                </div>
                <div class="mt-2.5 text-muted small"><i class="bi bi-exclamation-circle me-1 text-warning"></i>Requires dynamic release clearance</div>
            </div>
        </div>
    </div>

    <!-- Filter Panel Container -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('vendors.index') }}" method="GET" id="filterForm" class="row g-2">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search code, name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="mat_category" class="form-select form-select-sm bg-light">
                        <option value="">All Categories</option>
                        @foreach(['Cement', 'Steel', 'Cables', 'Electronics', 'Sanitary'] as $cat)
                            <option value="{{ $cat }}" {{ request('mat_category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="city" class="form-select form-select-sm bg-light">
                        <option value="">All Cities</option>
                        @foreach(['Dhaka', 'Chattogram', 'Khulna', 'Sylhet'] as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark flex-grow-1"><i class="bi bi-filter me-1"></i>Filter</button>
                    <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer"></i></button>
                    <button type="submit" name="export" value="csv" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-spreadsheet"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Core Interactive Data Matrix Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-list-task text-primary me-2"></i>Registered Vendor Matrix
            </h6>
            <button type="button" id="bulkDeleteBtn" class="btn btn-sm btn-outline-danger px-3 d-none" onclick="processBulkDelete()">
                <i class="bi bi-trash3 me-1"></i>Delete Selected
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="vendorDataTable">
                <thead class="table-light text-muted font-monospace small">
                    <tr>
                        <th width="40" class="ps-4">
                            <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Vendor Details</th>
                        <th>Contact Agent</th>
                        <th>Classification</th>
                        <th>Financial Footprint</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th width="120" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-dark small">
                    @forelse($vendors as $vendor)
                    <tr id="vendor-row-{{ $vendor->id }}">
                        <td class="ps-4">
                            <input type="checkbox" class="form-check-input vendor-checkbox" value="{{ $vendor->id }}" onclick="evaluateBulkVisibility()">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-wrapper bg-light rounded-2 border d-flex align-items-center justify-content-center" style="width:42px; height:42px;">
                                    @if($vendor->company_logo)
                                        <img src="{{ asset('storage/' . $vendor->company_logo) }}" class="rounded-2 img-fluid" alt="logo">
                                    @else
                                        <i class="bi bi-building text-muted fs-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0.5">{{ $vendor->company_name }}</div>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace" style="font-size:0.75rem;">
                                        {{ $vendor->vendor_code }}
                                    </span>
                                    <button class="btn btn-link p-0 text-muted ms-1 small align-baseline" onclick="navigator.clipboard.writeText('{{ $vendor->vendor_code }}')">
                                        <i class="bi bi-copy" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $vendor->contact_person }}</div>
                            <div class="text-muted small d-flex gap-2 mt-0.5">
                                <a href="tel:{{ $vendor->phone }}" class="text-decoration-none text-muted"><i class="bi bi-telephone me-1"></i>Call</a>
                                <a href="mailto:{{ $vendor->email }}" class="text-decoration-none text-muted"><i class="bi bi-envelope me-1"></i>Email</a>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $vendor->mat_category }}</div>
                            <span class="text-muted small">{{ $vendor->biz_category }} • {{ $vendor->city }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">৳{{ number_format($vendor->total_po_value, 2) }}</div>
                            <span class="text-muted small">{{ $vendor->total_pos }} Invoices • <span class="text-danger">৳{{ number_format($vendor->pending_payment, 0) }} pending</span></span>
                        </td>
                        <td>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $vendor->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2.5 py-1 {{ $vendor->status === 'Active' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                {{ $vendor->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1.5">
                                <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-sm btn-light border" title="View Details"><i class="bi bi-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-light border" title="Edit Parameters" data-bs-toggle="modal" data-bs-target="#editVendorModal{{ $vendor->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this vendor portfolio record safely?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted py-4">
                                <i class="bi bi-folder-x display-4 text-secondary"></i>
                                <h6 class="fw-bold mt-3 text-dark">No Vendor Records Discovered</h6>
                                <p class="small text-muted">Refine filter indices or register a new commercial vendor profile.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $vendors->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Scripts Engine Implementation -->
<script>
function toggleSelectAll(master) {
    document.querySelectorAll('.vendor-checkbox').forEach(box => box.checked = master.checked);
    evaluateBulkVisibility();
}

function evaluateBulkVisibility() {
    const checkedCount = document.querySelectorAll('.vendor-checkbox:checked').length;
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    if (checkedCount > 0) {
        bulkBtn.classList.remove('d-none');
        bulkBtn.innerText = `Delete Selected (${checkedCount})`;
    } else {
        bulkBtn.classList.add('d-none');
    }
}

function processBulkDelete() {
    if (!confirm('Safely soft-archive all selected vendor profile items?')) return;
    const selectedIds = Array.from(document.querySelectorAll('.vendor-checkbox:checked')).map(box => box.value);
    
    fetch('{{ route("vendors.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            selectedIds.forEach(id => document.getElementById(`vendor-row-${id}`).remove());
            document.getElementById('bulkDeleteBtn').classList.add('d-none');
            location.reload();
        }
    });
}
</script>
@endsection
<!-- Full Enterprise Register Vendor Modal -->
<div class="modal fade animate fade-in" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white py-3 border-0 px-4">
                <div>
                    <h5 class="modal-title font-sans fw-bold mb-0" id="addVendorModalLabel">
                        <i class="bi bi-building-add text-primary me-2"></i>New Supplier Onboarding Portfolio
                    </h5>
                    <small class="text-muted text-xs">Register new corporate accounts, financial vectors, and regulatory documentation handles.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Form Wrapper -->
            <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
                @csrf
                <div class="modal-body p-4 bg-light">
                    
                    <!-- SECTION 1: CORE COMPANY INFORMATION -->
                    <div class="card border-0 rounded-3 shadow-sm mb-3 bg-white">
                        <div class="card-header bg-white border-0 py-3 px-4">
                            <h6 class="fw-bold text-primary m-0 small text-uppercase font-monospace"><i class="bi bi-info-circle me-2"></i>1. Corporate Identity Profile</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Company Legal Name *</label>
                                    <input type="text" name="company_name" class="form-control form-control-sm rounded-2 @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required placeholder="e.g., Bashundhara Cement">
                                    @error('company_name') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Contact Agent Name *</label>
                                    <input type="text" name="contact_person" class="form-control form-control-sm rounded-2 @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" required placeholder="Account Manager Full Name">
                                    @error('contact_person') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Business Identity Registration No.</label>
                                    <input type="text" name="biz_reg_no" class="form-control form-control-sm rounded-2" value="{{ old('biz_reg_no') }}" placeholder="REG-XXXXXX">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Tax / BIN / VAT Identifier</label>
                                    <input type="text" name="tax_vat_no" class="form-control form-control-sm rounded-2" value="{{ old('tax_vat_no') }}" placeholder="VAT-XXXXXX">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">System Operational Status *</label>
                                    <select name="status" class="form-select form-select-sm rounded-2 bg-light border-0" required>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: COMMUNICATION ENDPOINTS & LOGISTICS -->
                    <div class="card border-0 rounded-3 shadow-sm mb-3 bg-white">
                        <div class="card-header bg-white border-0 py-3 px-4">
                            <h6 class="fw-bold text-primary m-0 small text-uppercase font-monospace"><i class="bi bi-geo-alt me-2"></i>2. Communication & Logistical Endpoints</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Primary Email Endpoint *</label>
                                    <input type="email" name="email" class="form-control form-control-sm rounded-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="procurement@company.com">
                                    @error('email') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Primary Telecom Value *</label>
                                    <input type="text" name="phone" class="form-control form-control-sm rounded-2 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="+8801...">
                                    @error('phone') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Alternative Phone Index</label>
                                    <input type="text" name="alt_phone" class="form-control form-control-sm rounded-2" value="{{ old('alt_phone') }}" placeholder="Landline or backup unit">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Corporate Web Domain URL</label>
                                    <input type="url" name="website" class="form-control form-control-sm rounded-2" value="{{ old('website') }}" placeholder="https://example.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Operational City Hub *</label>
                                    <input type="text" name="city" class="form-control form-control-sm rounded-2" value="{{ old('city') }}" required placeholder="e.g. Dhaka">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Full Head Office Dispatch Address *</label>
                                    <input type="text" name="address" class="form-control form-control-sm rounded-2" value="{{ old('address') }}" required placeholder="Street layout context details...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control form-control-sm rounded-2" value="{{ old('postal_code') }}" placeholder="1212">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Country</label>
                                    <input type="text" name="country" class="form-control form-control-sm rounded-2 bg-light border-0" value="Bangladesh" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: STRATEGIC OPERATION MATRIX -->
                    <div class="card border-0 rounded-3 shadow-sm mb-3 bg-white">
                        <div class="card-header bg-white border-0 py-3 px-4">
                            <h6 class="fw-bold text-primary m-0 small text-uppercase font-monospace"><i class="bi bi-sliders me-2"></i>3. Operational Classifications</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Business Classification Type *</label>
                                    <select name="biz_category" class="form-select form-select-sm rounded-2" required>
                                        <option value="">Choose Typology...</option>
                                        <option value="Manufacturer">Manufacturer</option>
                                        <option value="Distributor">Distributor / Wholesaler</option>
                                        <option value="Importer">Importer</option>
                                        <option value="Sub-Contractor">Sub-Contractor Service Provider</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Procurement Material Category *</label>
                                    <select name="mat_category" class="form-select form-select-sm rounded-2" required>
                                        <option value="">Select Domain...</option>
                                        <option value="Cement">Cement Supply</option>
                                        <option value="Steel">Steel / TMT Bars</option>
                                        <option value="Cables">Electrical & Cables</option>
                                        <option value="Sanitary">Sanitary & Fittings</option>
                                        <option value="Electronics">Electronics / HVAC</option>
                                        <option value="Aggregates">Aggregates & Ready-Mix</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Initial Evaluation Rating Index</label>
                                    <select name="rating" class="form-select form-select-sm rounded-2 bg-light border-0">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5 Baseline Standard)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5 Verified Supplier)</option>
                                        <option value="3">⭐⭐⭐ (3/5 Dynamic Account)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: FINANCIAL FOOTPRINTS & REGULATORY ATTACHMENTS -->
                    <div class="card border-0 rounded-3 shadow-sm bg-white">
                        <div class="card-header bg-white border-0 py-3 px-4">
                            <h6 class="fw-bold text-primary m-0 small text-uppercase font-monospace"><i class="bi bi-credit-card me-2"></i>4. Settlement Mechanics & Regulatory Verification Documents</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Target Clearing Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control form-control-sm rounded-2" placeholder="e.g. BRAC Bank Plc">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Settlement Clearing Account No.</label>
                                    <input type="text" name="bank_acc_no" class="form-control form-control-sm rounded-2" placeholder="Account sequence string...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Default Disbursement Method</label>
                                    <select name="pay_method" class="form-select form-select-sm rounded-2">
                                        <option value="EFT">Electronic Fund Transfer (EFT)</option>
                                        <option value="Cheque">Corporate Bank Cheque</option>
                                        <option value="Cash">Cash Account Ledger</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Default Maturity Terms</label>
                                    <select name="pay_terms" class="form-select form-select-sm rounded-2">
                                        <option value="Net 30">Net 30 Days Standard</option>
                                        <option value="Net 60">Net 60 Days Allocation</option>
                                        <option value="COD">Cash On Delivery (COD)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Upload Company Corporate Logo (Images Only)</label>
                                    <input type="file" name="company_logo" class="form-control form-control-sm rounded-2 @error('company_logo') is-invalid @enderror">
                                    @error('company_logo') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Regulatory Trade License Payload (PDF or Image)</label>
                                    <input type="file" name="trade_license" class="form-control form-control-sm rounded-2 @error('trade_license') is-invalid @enderror">
                                    @error('trade_license') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold text-xs mb-1">Strategic Procurement Notes & Directives</label>
                                    <textarea name="notes" class="form-control form-control-sm rounded-2" rows="2" placeholder="Append structural delivery caveats, logistical capabilities context, or special processing notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer bg-white border-0 px-4 py-3 shadow-sm d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light border px-4 rounded-2 py-2 fw-medium" data-bs-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 rounded-2 py-2 fw-medium text-white shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Commit Vendor Portfolio
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
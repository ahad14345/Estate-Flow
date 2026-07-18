@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Customer Management</h2>
        <p class="text-muted mb-0">A polished workspace for managing buyers, sellers, investors, and tenants.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('crm.customers.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Add Customer</a>
        <button class="btn btn-outline-primary"><i class="bi bi-download me-2"></i>Export</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, city" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Lead" {{ request('status') === 'Lead' ? 'selected' : '' }}>Lead</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Type</label>
                <select name="customer_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Buyer" {{ request('customer_type') === 'Buyer' ? 'selected' : '' }}>Buyer</option>
                    <option value="Seller" {{ request('customer_type') === 'Seller' ? 'selected' : '' }}>Seller</option>
                    <option value="Investor" {{ request('customer_type') === 'Investor' ? 'selected' : '' }}>Investor</option>
                    <option value="Tenant" {{ request('customer_type') === 'Tenant' ? 'selected' : '' }}>Tenant</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $customer->full_name }}</div>
                                    <div class="small text-muted">{{ $customer->customer_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-muted">{{ $customer->email }}</div>
                            <div class="small text-muted">{{ $customer->phone_number }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $customer->city ?? '—' }}</div>
                            <div class="small text-muted">{{ $customer->preferred_property_type ?? '—' }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $customer->customer_type }}</span></td>
                        <td><span class="badge bg-info-subtle text-info-emphasis">{{ $customer->status }}</span></td>
                        <td>{{ $customer->assigned_employee ?? 'Unassigned' }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('crm.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('crm.customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('crm.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                            No customers found yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $customers->links() }}</div>
@endsection

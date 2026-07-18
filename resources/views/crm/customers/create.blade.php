@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Create Customer</h3>
                <p class="text-muted mb-0">Add a new customer profile and start building the relationship.</p>
            </div>
        </div>
        <form action="{{ route('crm.customers.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">@error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}">@error('email')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">@error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">National ID</label><input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">@error('national_id')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>@error('address')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city') }}">@error('city')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Customer Type</label><select name="customer_type" class="form-select"><option value="Buyer">Buyer</option><option value="Seller">Seller</option><option value="Investor">Investor</option><option value="Tenant">Tenant</option></select>@error('customer_type')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Preferred Property Type</label><input type="text" name="preferred_property_type" class="form-control" value="{{ old('preferred_property_type') }}">@error('preferred_property_type')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Budget</label><input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget') }}">@error('budget')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Assigned Employee</label><input type="text" name="assigned_employee" class="form-control" value="{{ old('assigned_employee') }}">@error('assigned_employee')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="Lead">Lead</option><option value="Active">Active</option><option value="Closed">Closed</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Customer</button>
                <a href="{{ route('crm.customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

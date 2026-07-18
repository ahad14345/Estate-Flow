@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Edit Customer</h3>
                <p class="text-muted mb-0">Refine the profile and keep your CRM records current.</p>
            </div>
        </div>
        <form action="{{ route('crm.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name', $customer->full_name) }}">@error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">@error('email')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $customer->phone_number) }}">@error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">National ID</label><input type="text" name="national_id" class="form-control" value="{{ old('national_id', $customer->national_id) }}">@error('national_id')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>@error('address')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}">@error('city')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Customer Type</label><select name="customer_type" class="form-select"><option value="Buyer" {{ old('customer_type', $customer->customer_type) === 'Buyer' ? 'selected' : '' }}>Buyer</option><option value="Seller" {{ old('customer_type', $customer->customer_type) === 'Seller' ? 'selected' : '' }}>Seller</option><option value="Investor" {{ old('customer_type', $customer->customer_type) === 'Investor' ? 'selected' : '' }}>Investor</option><option value="Tenant" {{ old('customer_type', $customer->customer_type) === 'Tenant' ? 'selected' : '' }}>Tenant</option></select>@error('customer_type')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Preferred Property Type</label><input type="text" name="preferred_property_type" class="form-control" value="{{ old('preferred_property_type', $customer->preferred_property_type) }}">@error('preferred_property_type')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Budget</label><input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget', $customer->budget) }}">@error('budget')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Assigned Employee</label><input type="text" name="assigned_employee" class="form-control" value="{{ old('assigned_employee', $customer->assigned_employee) }}">@error('assigned_employee')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="Lead" {{ old('status', $customer->status) === 'Lead' ? 'selected' : '' }}>Lead</option><option value="Active" {{ old('status', $customer->status) === 'Active' ? 'selected' : '' }}>Active</option><option value="Closed" {{ old('status', $customer->status) === 'Closed' ? 'selected' : '' }}>Closed</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $customer->notes) }}</textarea>@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Customer</button>
                <a href="{{ route('crm.customers.show', $customer) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="fw-bold mb-4">Create Lead</h3>
        <form action="{{ route('crm.leads.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">@error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}">@error('email')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">@error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Lead Source</label><select name="lead_source" class="form-select"><option value="Website">Website</option><option value="Facebook">Facebook</option><option value="Referral">Referral</option><option value="Walk-in">Walk-in</option><option value="Phone Call">Phone Call</option></select>@error('lead_source')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Priority</label><select name="priority" class="form-select"><option value="Low">Low</option><option value="Medium">Medium</option><option value="High">High</option></select>@error('priority')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="New">New</option><option value="Contacted">Contacted</option><option value="Interested">Interested</option><option value="Negotiation">Negotiation</option><option value="Converted">Converted</option><option value="Lost">Lost</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Assigned Employee</label><input type="text" name="assigned_employee" class="form-control" value="{{ old('assigned_employee') }}">@error('assigned_employee')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Budget</label><input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget') }}">@error('budget')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-primary">Save Lead</button><a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@endsection

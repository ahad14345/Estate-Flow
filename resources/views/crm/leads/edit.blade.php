@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="fw-bold mb-4">Edit Lead</h3>
        <form action="{{ route('crm.leads.update', $lead) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name', $lead->full_name) }}">@error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">@error('email')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $lead->phone_number) }}">@error('phone_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Lead Source</label><select name="lead_source" class="form-select"><option value="Website" {{ old('lead_source', $lead->lead_source) === 'Website' ? 'selected' : '' }}>Website</option><option value="Facebook" {{ old('lead_source', $lead->lead_source) === 'Facebook' ? 'selected' : '' }}>Facebook</option><option value="Referral" {{ old('lead_source', $lead->lead_source) === 'Referral' ? 'selected' : '' }}>Referral</option><option value="Walk-in" {{ old('lead_source', $lead->lead_source) === 'Walk-in' ? 'selected' : '' }}>Walk-in</option><option value="Phone Call" {{ old('lead_source', $lead->lead_source) === 'Phone Call' ? 'selected' : '' }}>Phone Call</option></select>@error('lead_source')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Priority</label><select name="priority" class="form-select"><option value="Low" {{ old('priority', $lead->priority) === 'Low' ? 'selected' : '' }}>Low</option><option value="Medium" {{ old('priority', $lead->priority) === 'Medium' ? 'selected' : '' }}>Medium</option><option value="High" {{ old('priority', $lead->priority) === 'High' ? 'selected' : '' }}>High</option></select>@error('priority')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="New" {{ old('status', $lead->status) === 'New' ? 'selected' : '' }}>New</option><option value="Contacted" {{ old('status', $lead->status) === 'Contacted' ? 'selected' : '' }}>Contacted</option><option value="Interested" {{ old('status', $lead->status) === 'Interested' ? 'selected' : '' }}>Interested</option><option value="Negotiation" {{ old('status', $lead->status) === 'Negotiation' ? 'selected' : '' }}>Negotiation</option><option value="Converted" {{ old('status', $lead->status) === 'Converted' ? 'selected' : '' }}>Converted</option><option value="Lost" {{ old('status', $lead->status) === 'Lost' ? 'selected' : '' }}>Lost</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Assigned Employee</label><input type="text" name="assigned_employee" class="form-control" value="{{ old('assigned_employee', $lead->assigned_employee) }}">@error('assigned_employee')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Budget</label><input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget', $lead->budget) }}">@error('budget')<div class="text-danger small">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead->notes) }}</textarea>@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-primary">Update Lead</button><a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@endsection

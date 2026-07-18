@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.follow-ups.index') }}">Follow-ups</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="fw-bold mb-4">Schedule Follow-up</h3>
        <form action="{{ route('crm.follow-ups.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Customer</label><select name="customer_id" class="form-select"><option value="">Select customer</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->full_name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Lead</label><select name="lead_id" class="form-select"><option value="">Select lead</option>@foreach($leads as $lead)<option value="{{ $lead->id }}">{{ $lead->full_name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Type</label><select name="follow_up_type" class="form-select"><option value="Call">Call</option><option value="Meeting">Meeting</option><option value="Email">Email</option><option value="Site Visit">Site Visit</option></select></div>
                <div class="col-md-6"><label class="form-label">Subject</label><input type="text" name="subject" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Scheduled At</label><input type="datetime-local" name="scheduled_at" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Reminder Status</label><select name="reminder_status" class="form-select"><option value="Pending">Pending</option><option value="Sent">Sent</option><option value="Completed">Completed</option></select></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="Pending">Pending</option><option value="Completed">Completed</option></select></div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-primary">Save Follow-up</button><a href="{{ route('crm.follow-ups.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@endsection

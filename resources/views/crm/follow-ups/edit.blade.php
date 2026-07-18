@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.follow-ups.index') }}">Follow-ups</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="fw-bold mb-4">Edit Follow-up</h3>
        <form action="{{ route('crm.follow-ups.update', $followUp) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Customer</label><select name="customer_id" class="form-select"><option value="">Select customer</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" {{ old('customer_id', $followUp->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->full_name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Lead</label><select name="lead_id" class="form-select"><option value="">Select lead</option>@foreach($leads as $lead)<option value="{{ $lead->id }}" {{ old('lead_id', $followUp->lead_id) == $lead->id ? 'selected' : '' }}>{{ $lead->full_name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Type</label><select name="follow_up_type" class="form-select"><option value="Call" {{ old('follow_up_type', $followUp->follow_up_type) === 'Call' ? 'selected' : '' }}>Call</option><option value="Meeting" {{ old('follow_up_type', $followUp->follow_up_type) === 'Meeting' ? 'selected' : '' }}>Meeting</option><option value="Email" {{ old('follow_up_type', $followUp->follow_up_type) === 'Email' ? 'selected' : '' }}>Email</option><option value="Site Visit" {{ old('follow_up_type', $followUp->follow_up_type) === 'Site Visit' ? 'selected' : '' }}>Site Visit</option></select></div>
                <div class="col-md-6"><label class="form-label">Subject</label><input type="text" name="subject" class="form-control" value="{{ old('subject', $followUp->subject) }}"></div>
                <div class="col-md-6"><label class="form-label">Scheduled At</label><input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $followUp->scheduled_at->format('Y-m-d\TH:i')) }}"></div>
                <div class="col-md-6"><label class="form-label">Reminder Status</label><select name="reminder_status" class="form-select"><option value="Pending" {{ old('reminder_status', $followUp->reminder_status) === 'Pending' ? 'selected' : '' }}>Pending</option><option value="Sent" {{ old('reminder_status', $followUp->reminder_status) === 'Sent' ? 'selected' : '' }}>Sent</option><option value="Completed" {{ old('reminder_status', $followUp->reminder_status) === 'Completed' ? 'selected' : '' }}>Completed</option></select></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="Pending" {{ old('status', $followUp->status) === 'Pending' ? 'selected' : '' }}>Pending</option><option value="Completed" {{ old('status', $followUp->status) === 'Completed' ? 'selected' : '' }}>Completed</option></select></div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $followUp->notes) }}</textarea></div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-primary">Update Follow-up</button><a href="{{ route('crm.follow-ups.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@endsection

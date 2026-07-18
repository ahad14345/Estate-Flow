@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h3 class="fw-bold">{{ $lead->full_name }}</h3>
        <p class="text-muted">{{ $lead->lead_code }}</p>
        <div class="row g-3">
            <div class="col-md-6"><strong>Email:</strong> {{ $lead->email }}</div>
            <div class="col-md-6"><strong>Phone:</strong> {{ $lead->phone_number }}</div>
            <div class="col-md-6"><strong>Source:</strong> {{ $lead->lead_source }}</div>
            <div class="col-md-6"><strong>Priority:</strong> {{ $lead->priority }}</div>
            <div class="col-md-6"><strong>Status:</strong> {{ $lead->status }}</div>
            <div class="col-md-6"><strong>Assigned Employee:</strong> {{ $lead->assigned_employee }}</div>
            <div class="col-12"><strong>Notes:</strong> {{ $lead->notes }}</div>
        </div>
        <div class="mt-4"><a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-primary">Edit</a></div>
    </div>
</div>
@endsection

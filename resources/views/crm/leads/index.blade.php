@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Leads</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Lead Management</h2>
        <p class="text-muted mb-0">Track every opportunity from initial interest to conversion.</p>
    </div>
    <a href="{{ route('crm.leads.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>New Lead</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="New" {{ request('status') === 'New' ? 'selected' : '' }}>New</option>
                    <option value="Contacted" {{ request('status') === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="Interested" {{ request('status') === 'Interested' ? 'selected' : '' }}>Interested</option>
                    <option value="Negotiation" {{ request('status') === 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="Converted" {{ request('status') === 'Converted' ? 'selected' : '' }}>Converted</option>
                    <option value="Lost" {{ request('status') === 'Lost' ? 'selected' : '' }}>Lost</option>
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
                <tr><th>Lead</th><th>Contact</th><th>Source</th><th>Priority</th><th>Status</th><th>Owner</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $lead->full_name }}</div>
                            <div class="small text-muted">{{ $lead->lead_code }}</div>
                        </td>
                        <td>
                            <div class="small text-muted">{{ $lead->email }}</div>
                            <div class="small text-muted">{{ $lead->phone_number }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $lead->lead_source }}</span></td>
                        <td><span class="badge bg-warning-subtle text-warning-emphasis">{{ $lead->priority }}</span></td>
                        <td><span class="badge bg-info-subtle text-info-emphasis">{{ $lead->status }}</span></td>
                        <td>{{ $lead->assigned_employee ?? 'Unassigned' }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('crm.leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">No leads found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $leads->links() }}</div>
@endsection

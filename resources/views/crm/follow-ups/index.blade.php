@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Follow-ups</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Follow-up Management</h2>
        <p class="text-muted mb-0">Stay on top of every client conversation and reminder.</p>
    </div>
    <a href="{{ route('crm.follow-ups.create') }}" class="btn btn-primary"><i class="bi bi-calendar-plus me-2"></i>New Follow-up</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Today’s Follow-ups</h5>
                <div class="d-flex flex-column gap-2">
                    @forelse($followUps->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay()) as $followUp)
                        <div class="border rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $followUp->subject }}</div>
                                    <div class="text-muted small">{{ $followUp->customer?->full_name ?? $followUp->lead?->full_name ?? '—' }}</div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">{{ $followUp->follow_up_type }}</span>
                            </div>
                            <div class="text-muted small mt-2"><i class="bi bi-clock me-1"></i>{{ $followUp->scheduled_at->format('H:i') }}</div>
                        </div>
                    @empty
                        <div class="text-muted py-3">No follow-ups scheduled for today.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Upcoming Schedule</h5>
                <div class="d-flex flex-column gap-2">
                    @forelse($followUps->take(6) as $followUp)
                        <div class="border rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $followUp->subject }}</div>
                                    <div class="text-muted small">{{ $followUp->scheduled_at->format('M d, Y H:i') }}</div>
                                </div>
                                <span class="badge bg-success-subtle text-success">{{ $followUp->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted py-3">No upcoming follow-ups.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Subject</th><th>Type</th><th>Customer</th><th>Scheduled</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($followUps as $followUp)
                    <tr>
                        <td>{{ $followUp->subject }}</td>
                        <td><span class="badge bg-light text-dark">{{ $followUp->follow_up_type }}</span></td>
                        <td>{{ $followUp->customer?->full_name ?? $followUp->lead?->full_name ?? '—' }}</td>
                        <td>{{ $followUp->scheduled_at->format('M d, Y H:i') }}</td>
                        <td><span class="badge bg-info-subtle text-info-emphasis">{{ $followUp->status }}</span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('crm.follow-ups.edit', $followUp) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('crm.follow-ups.destroy', $followUp) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this follow-up?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No follow-ups found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $followUps->links() }}</div>
@endsection

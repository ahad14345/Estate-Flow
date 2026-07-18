@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('crm.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Customer Profile</h2>
        <p class="text-muted mb-0">Detailed view of the customer journey, interests, and communications.</p>
    </div>
    <a href="{{ route('crm.customers.edit', $customer) }}" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Edit Profile</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:58px;height:58px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $customer->full_name }}</h3>
                        <div class="text-muted">{{ $customer->customer_code }}</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $customer->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">Phone</div>
                            <div class="fw-semibold">{{ $customer->phone_number }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">City</div>
                            <div class="fw-semibold">{{ $customer->city ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">Customer Type</div>
                            <div class="fw-semibold">{{ $customer->customer_type }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">Budget</div>
                            <div class="fw-semibold">{{ number_format($customer->budget, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">{{ $customer->status }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded-3">
                            <div class="text-muted small">Address</div>
                            <div class="fw-semibold">{{ $customer->address ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded-3">
                            <div class="text-muted small">Notes</div>
                            <div class="fw-semibold">{{ $customer->notes ?? 'No notes recorded.' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Property Interests</h5>
                @forelse($customer->propertyInterests as $interest)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="fw-semibold">{{ $interest->property_name }}</div>
                        <div class="small text-muted">{{ $interest->interest_level }} • {{ $interest->visit_date ?? 'Scheduled soon' }}</div>
                    </div>
                @empty
                    <div class="text-muted py-3">No property interests yet.</div>
                @endforelse
                <form action="{{ route('crm.customers.interests.store', $customer) }}" method="POST" class="mt-3">
                    @csrf
                    <input type="text" name="property_name" class="form-control mb-2" placeholder="Property name">
                    <input type="text" name="property_reference" class="form-control mb-2" placeholder="Reference">
                    <select name="interest_level" class="form-select mb-2">
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                    <input type="date" name="visit_date" class="form-control mb-2">
                    <textarea name="remarks" class="form-control mb-2" rows="2" placeholder="Remarks"></textarea>
                    <button class="btn btn-primary btn-sm">Add Interest</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Activity Timeline</h5>
        <div class="list-group list-group-flush">
            @forelse($customer->activities as $activity)
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="fw-semibold">{{ $activity->subject }}</div>
                            <div class="text-muted small">{{ $activity->activity_type }} • {{ $activity->description }}</div>
                        </div>
                        <span class="text-muted small">{{ $activity->occurred_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-muted py-3">No timeline entries yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

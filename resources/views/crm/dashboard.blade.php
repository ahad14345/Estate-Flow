@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">CRM Dashboard</h2>
        <p class="text-muted mb-0">A modern overview of customers, leads, and follow-ups across your real estate pipeline.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.customers.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>New Customer</a>
        <a href="{{ route('crm.leads.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle me-2"></i>New Lead</a>
    </div>
</div>

<div class="row g-4 mb-4">
    @php($stats = [
        ['label' => 'Total Customers', 'value' => $totalCustomers, 'icon' => 'people', 'color' => 'primary'],
        ['label' => 'Active Customers', 'value' => $activeCustomers, 'icon' => 'person-check', 'color' => 'success'],
        ['label' => 'Leads', 'value' => $leads, 'icon' => 'lightning', 'color' => 'info'],
        ['label' => 'Follow-ups Due Today', 'value' => $followUpsDueToday, 'icon' => 'calendar2-event', 'color' => 'warning'],
        ['label' => 'New Customers This Month', 'value' => $customers->where('created_at', '>=', now()->startOfMonth())->count(), 'icon' => 'graph-up-arrow', 'color' => 'secondary'],
        ['label' => 'Lead Conversion Rate', 'value' => $leads->count() ? round(($customerStats['Converted'] ?? 0) / $leads->count() * 100, 1) . '%' : '0%', 'icon' => 'bar-chart-line', 'color' => 'danger'],
    ])
    @foreach($stats as $stat)
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-semibold">{{ $stat['label'] }}</div>
                            <div class="display-6 fw-bold text-{{ $stat['color'] }} mt-2">{{ $stat['value'] }}</div>
                        </div>
                        <div class="rounded-circle bg-{{ $stat['color'] }} bg-opacity-10 p-3 text-{{ $stat['color'] }}">
                            <i class="bi bi-{{ $stat['icon'] }} fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Customer Performance</h5>
                        <p class="text-muted mb-0">Monthly customer activity across the CRM.</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">Live</span>
                </div>
                <canvas id="customerStatsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Lead Conversion</h5>
                <canvas id="leadStatsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Recent Activities</h5>
                        <p class="text-muted mb-0">Latest CRM updates and interactions.</p>
                    </div>
                    <a href="{{ route('crm.customers.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $activity->subject }}</div>
                                    <div class="text-muted small">{{ $activity->description }} • {{ $activity->occurred_at->diffForHumans() }}</div>
                                </div>
                                <span class="badge bg-light text-muted">{{ $activity->activity_type }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted py-3">No recent activities yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Upcoming Follow-ups</h5>
                        <p class="text-muted mb-0">Scheduled conversations close to today.</p>
                    </div>
                    <a href="{{ route('crm.follow-ups.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($followUps->take(5) as $followUp)
                        <div class="border rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $followUp->subject }}</div>
                                    <div class="text-muted small">{{ $followUp->customer?->full_name ?? $followUp->lead?->full_name ?? '—' }}</div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">{{ $followUp->follow_up_type }}</span>
                            </div>
                            <div class="text-muted small mt-2"><i class="bi bi-clock me-1"></i>{{ $followUp->scheduled_at->format('M d, Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="text-muted py-3">No upcoming follow-ups scheduled.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const customerStats = @json($customerStats);
    const leadStats = @json($leadStats);
    const cityStats = @json($cityStats);

    new Chart(document.getElementById('customerStatsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(customerStats),
            datasets: [{ label: 'Customers', data: Object.values(customerStats), backgroundColor: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'] }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('leadStatsChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(leadStats),
            datasets: [{ data: Object.values(leadStats), backgroundColor: ['#2563eb', '#60a5fa', '#1d4ed8', '#93c5fd'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endsection

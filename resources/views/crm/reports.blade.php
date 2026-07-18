@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">CRM Reports</h2>
        <p class="text-muted mb-0">Professional visual insights for growth, conversion, and customer segmentation.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary"><i class="bi bi-file-earmark-pdf me-2"></i>Export PDF</button>
        <button class="btn btn-primary"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Customer Growth</h5>
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Customer Type Distribution</h5>
                <canvas id="typeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Lead Conversion</h5>
                <canvas id="conversionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Top Cities</h5>
                <canvas id="cityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const growthData = [12, 18, 24, 29, 34, 37];
    const typeData = {
        labels: ['Buyer', 'Seller', 'Investor', 'Tenant'],
        values: [18, 9, 5, 8]
    };
    const conversionData = {
        labels: ['New', 'Contacted', 'Interested', 'Converted'],
        values: [8, 5, 4, 3]
    };
    const cityData = {
        labels: ['Dhaka', 'Chattogram', 'Sylhet', 'Khulna'],
        values: [14, 8, 5, 3]
    };

    new Chart(document.getElementById('growthChart'), { type: 'line', data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ label: 'Customers', data: growthData, borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.15)', fill: true, tension: 0.4 }] }, options: { scales: { y: { beginAtZero: true } } } });

    new Chart(document.getElementById('typeChart'), { type: 'doughnut', data: { labels: typeData.labels, datasets: [{ data: typeData.values, backgroundColor: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'] }] }, options: { plugins: { legend: { position: 'bottom' } } } });

    new Chart(document.getElementById('conversionChart'), { type: 'bar', data: { labels: conversionData.labels, datasets: [{ label: 'Leads', data: conversionData.values, backgroundColor: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'] }] }, options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });

    new Chart(document.getElementById('cityChart'), { type: 'pie', data: { labels: cityData.labels, datasets: [{ data: cityData.values, backgroundColor: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'] }] }, options: { plugins: { legend: { position: 'bottom' } } } });
</script>
@endsection

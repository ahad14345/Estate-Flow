@extends('layouts.app')

@section('page_title', 'Properties Management - EstateFlow ERP')

@section('content')
<div class="space-y-6">
    <!-- Filter and Search Panel -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Text Search -->
            <div class="flex flex-col space-y-1">
                <label for="search" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Search Title</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <i class="bi bi-search"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        value="{{ request('search') }}" 
                        placeholder="e.g. Luxury Apartment..." 
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-gray-700"
                    >
                </div>
            </div>

            <!-- Type Filter -->
            <div class="flex flex-col space-y-1">
                <label for="type" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Property Type</label>
                <select 
                    name="type" 
                    id="type" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-gray-700 bg-white"
                >
                    <option value="">All Types</option>
                    <option value="Apartment" {{ request('type') === 'Apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="Duplex" {{ request('type') === 'Duplex' ? 'selected' : '' }}>Duplex</option>
                    <option value="Penthouse" {{ request('type') === 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                    <option value="Commercial" {{ request('type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="flex flex-col space-y-1">
                <label for="status" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</label>
                <select 
                    name="status" 
                    id="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-gray-700 bg-white"
                >
                    <option value="">All Statuses</option>
                    <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Reserved" {{ request('status') === 'Reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="Sold" {{ request('status') === 'Sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors flex items-center justify-center space-x-1 shadow-sm">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Filter</span>
                </button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('properties.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded text-sm transition-colors flex items-center justify-center shadow-sm" title="Clear Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table View -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Properties / Units</h2>
            <a href="{{ route('properties.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none text-white px-4 py-2 rounded text-sm font-medium transition-colors shadow-sm flex items-center">
                <i class="bi bi-plus-lg me-1"></i> Add Property Unit
            </a>
        </div>
        
        <div class="overflow-x-auto p-6">
            <table class="table w-full border-collapse text-left text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold">
                        <th class="p-3">Unit Title</th>
                        <th class="p-3">Assigned Project</th>
                        <th class="p-3">Type / Size</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($properties as $property)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="p-3">
                                <div class="font-semibold text-gray-900">{{ $property->title }}</div>
                                @if(!empty($property->block))
                                    <div class="text-xs text-slate-400">Block: {{ $property->block }}</div>
                                @endif
                            </td>
                            
                            <!-- Hydrated from PL/SQL Ref Cursor Outer Join Selection -->
                            <td class="p-3 font-medium text-indigo-600">
                                {{ $property->project_name ?? 'Unassigned' }}
                            </td>
                            
                            <td class="p-3">
                                <div>{{ $property->type }}</div>
                                <div class="text-xs text-gray-400">{{ $property->size_sqft ?? 'N/A' }} Sq. Ft.</div>
                            </td>
                            <td class="p-3 font-semibold text-gray-800">${{ number_format($property->price, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border
                                    {{ $property->status === 'Available' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $property->status === 'Sold' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                                    {{ $property->status === 'Reserved' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                ">
                                    {{ $property->status }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('properties.edit', $property->id) }}" class="text-indigo-600 hover:text-indigo-900 text-base" title="Edit Unit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this property unit layout record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0 inline align-middle text-base cursor-pointer" title="Delete Unit">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                    <span>No properties match the chosen filters. Try widening your criteria or adding a new layout above.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination Footer Link Setup -->
            <div class="mt-4">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
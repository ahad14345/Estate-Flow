@extends('layouts.app')

@section('page_title', 'Add Property Unit - EstateFlow ERP')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-3xl mx-auto">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Add Property Unit</h2>
    </div>
    
    <form action="{{ route('properties.store') }}" method="POST" class="p-6 space-y-4">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Parent Project *</label>
                <select name="project_id" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 border" required>
                    <option value="">-- Choose Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Title / Flat Number *</label>
                <input type="text" name="title" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" value="{{ old('title') }}" placeholder="e.g. Flat 4B, Building A" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type *</label>
                <select name="type" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 border" required>
                    <option value="Apartment" {{ old('type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="Commercial" {{ old('type') == 'Commercial' ? 'selected' : '' }}>Commercial Space</option>
                    <option value="Penthouse" {{ old('type') == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                    <option value="Plot" {{ old('type') == 'Plot' ? 'selected' : '' }}>Plot</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Block / Phase</label>
                <input type="text" name="block" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" value="{{ old('block') }}" placeholder="e.g. Sector 3">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Size (Sq. Ft.)</label>
                <input type="text" name="size_sqft" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" value="{{ old('size_sqft') }}" placeholder="e.g. 1450">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                <select name="status" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 border" required>
                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Reserved" {{ old('status') == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="Sold" {{ old('status') == 'Sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Base Valuation Price ($) *</label>
                <input type="number" step="0.01" name="price" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" value="{{ old('price', '0.00') }}" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Additional Specifications / Details</label>
            <textarea name="details" rows="3" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="Facing direction, structural additions, variations..."></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
            <a href="{{ route('properties.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm font-medium shadow-sm">Save Property</button>
        </div>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('page_title', 'Projects List - EstateFlow ERP')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Projects Management</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none text-white px-4 py-2 rounded text-sm font-medium">
            <i class="bi bi-plus-lg me-1"></i> Add Project
        </a>
    </div>
    
    <div class="overflow-x-auto p-6">
        <!-- Project Search & Filter Bar Section -->
<form action="{{ route('projects.index') }}" method="GET" class="p-6 bg-slate-50 border-b border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Search Keywords</label>
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search project name..." class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border pl-8">
            <i class="bi bi-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
        </div>
    </div>
    
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status State</label>
        <select name="status" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border">
            <option value="">-- All Statuses --</option>
            <option value="Planning" {{ request('status') === 'Planning' ? 'selected' : '' }}>Planning</option>
            <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="On Hold" {{ request('status') === 'On Hold' ? 'selected' : '' }}>On Hold</option>
        </select>
    </div>

    <div class="flex items-end gap-2">
        <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium py-2 px-4 rounded shadow-sm transition-colors h-9">
            Apply Filters
        </button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('projects.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-4 rounded transition-colors h-9 flex items-center justify-center">
                Clear
            </a>
        @endif
    </div>
</form>
        <table class="table w-full border-collapse text-left text-sm text-gray-600">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold">
                    <th class="p-3">ID</th>
                    <th class="p-3">Project Name</th>
                    <th class="p-3">Budget</th>
                    <th class="p-3">Timeline</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="p-3 font-medium text-gray-900">#{{ $project->id }}</td>
                        <td class="p-3">
                            <div class="font-semibold text-gray-900">{{ $project->name }}</div>
                            <div class="text-xs text-gray-400 truncate max-w-xs">{{ $project->description }}</div>
                        </td>
                        <td class="p-3 font-semibold text-gray-800">${{ number_format($project->budget, 2) }}</td>
                        <td class="p-3 text-xs">
                            <div><strong>Start:</strong> {{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</div>
                            <div><strong>End:</strong> {{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</div>
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                {{ $project->status === 'In Progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $project->status === 'Planning' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                {{ $project->status === 'Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $project->status === 'On Hold' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                            ">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('projects.edit', $project->id) }}" class="text-indigo-600 hover:text-indigo-900"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-none border-none p-0"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">No projects found. Add your first project data to start records tracking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Project; // Included in case your create/edit forms need project options
use App\Models\CustomerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index(Request $request)
    {
        $query = Property::with('project');

        // 1. Title Keyword Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        // 2. Unit Status Dropdown Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 3. Property Classification Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $projects = Project::all(); // Pass projects if your property belongs to a project dropdown
        return view('properties.create', compact('projects'));
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => ['nullable', 'exists:projects,id'],
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string'],
            'price'       => ['nullable', 'numeric'],
            'status'      => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $property = Property::create($validated);

        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load('project');
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $projects = Project::all();
        return view('properties.edit', compact('property', 'projects'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'project_id'  => ['nullable', 'exists:projects,id'],
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string'],
            'price'       => ['nullable', 'numeric'],
            'status'      => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $property->update($validated);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }

    /* =========================================================================
     * RESOURCE ALIASES (Matches custom naming conventions if needed)
     * ========================================================================= */

    public function propertiesCreate()
    {
        return $this->create();
    }

    public function propertiesStore(Request $request)
    {
        return $this->store($request);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyController extends Controller
{
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
}
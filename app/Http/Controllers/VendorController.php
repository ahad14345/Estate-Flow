<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreVendorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        // High-Performance Filtering Matrix
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(company_name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(vendor_code) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(contact_person) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('biz_category')) $query->where('biz_category', $request->biz_category);
        if ($request->filled('mat_category')) $query->where('mat_category', $request->mat_category);
        if ($request->filled('city')) $query->whereRaw('LOWER(city) = ?', [strtolower($request->city)]);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('pay_method')) $query->where('pay_method', $request->pay_method);

        if ($request->filled('min_value')) $query->where('total_po_value', '>=', $request->min_value);
        if ($request->filled('max_value')) $query->where('total_po_value', '<=', $request->max_value);

        if ($request->filled('reg_date')) {
            $query->whereDate('created_at', $request->reg_date);
        }

        // Sorting Engine
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Core Aggregate KPIs Block
        $metrics = [
            'total' => Vendor::count(),
            'active' => Vendor::where('status', 'Active')->count(),
            'inactive' => Vendor::where('status', 'Inactive')->count(),
            'total_pos' => Vendor::sum('total_pos'),
            'total_value' => Vendor::sum('total_po_value'),
            'pending_payment' => Vendor::sum('pending_payment'),
            'new_this_month' => Vendor::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count(),
        ];

        // Export/Print Engine Hooks
        if ($request->has('export')) {
            return $this->handleExport($query->get(), $request->export);
        }

        $vendors = $query->paginate($request->input('per_page', 10))->withQueryString();

        return view('vendors.index', compact('vendors', 'metrics'));
    }

    public function store(StoreVendorRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('vendors/logos', 'public');
        }
        if ($request->hasFile('trade_license')) {
            $data['trade_license'] = $request->file('trade_license')->store('vendors/docs', 'public');
        }

        $data['created_by'] = auth()->id();

        Vendor::create($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor portfolio generated successfully.');
    }

    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    public function update(StoreVendorRequest $request, Vendor $vendor)
    {
        $data = $request->validated();

        if ($request->hasFile('company_logo')) {
            if ($vendor->company_logo) Storage::disk('public')->delete($vendor->company_logo);
            $data['company_logo'] = $request->file('company_logo')->store('vendors/logos', 'public');
        }
        if ($request->hasFile('trade_license')) {
            if ($vendor->trade_license) Storage::disk('public')->delete($vendor->trade_license);
            $data['trade_license'] = $request->file('trade_license')->store('vendors/docs', 'public');
        }

        $vendor->update($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor profile mutated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor soft-archived successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Vendor::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected vendors soft-archived safely.']);
    }

    private function handleExport($data, $type)
    {
        $filename = "vendors_export_" . now()->format('Ymd_His');
        
        if ($type === 'csv' || $type === 'xls') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
            $output = fopen('php://output', 'w');
            
            fputcsv($output, ['Vendor Code', 'Company Name', 'Contact Person', 'Email', 'Phone', 'Category', 'Status', 'Total Value']);
            foreach ($data as $row) {
                fputcsv($output, [$row->vendor_code, $row->company_name, $row->contact_person, $row->email, $row->phone, $row->mat_category, $row->status, $row->total_po_value]);
            }
            fclose($output);
            exit;
        }
    }
}
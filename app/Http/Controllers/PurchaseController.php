<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::query();

        // Multi-Parameter Filtering Block
        if ($request->filled('search')) {
            $search = '%' . strtoupper($request->search) . '%';
            $query->where(function($q) use ($search) {
                $q->whereRaw('UPPER(purchase_order_no) LIKE ?', [$search])
                  ->orWhereRaw('UPPER(vendor_name) LIKE ?', [$search])
                  ->orWhereRaw('UPPER(item_name) LIKE ?', [$search])
                  ->orWhereRaw('UPPER(created_by) LIKE ?', [$search]);
            });
        }

        foreach (['vendor_name', 'project_name', 'category', 'payment_status', 'purchase_status'] as $filterKey) {
            if ($request->filled($filterKey)) {
                $query->where($filterKey, $request->input($filterKey));
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('min_amount') && $request->filled('max_amount')) {
            $query->whereBetween('total_amount', [$request->min_amount, $request->max_amount]);
        }

        // Sorting Logic Integration
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = ($sortBy === 'total_amount') ? 'desc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->input('per_page', 10);
        $purchases = $query->paginate($perPage)->withQueryString();

        // Structural Dashboard Aggregates Matrix
        $metrics = [
            'total_count'    => Purchase::count(),
            'total_vendors'  => Purchase::distinct('vendor_name')->count('vendor_name'),
            'total_contractors' => Purchase::whereNotNull('contractor_name')->distinct('contractor_name')->count('contractor_name'),
            'pending_count'  => Purchase::where('purchase_status', 'Pending')->count(),
            'completed_count'=> Purchase::where('purchase_status', 'Delivered')->count(),
            'monthly_amount' => Purchase::whereBetween('purchase_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('total_amount'),
        ];

        // Component Constants Arrays for Select Elements
        $categories = ['Construction Materials', 'Electrical Materials', 'Plumbing Materials', 'Interior Materials', 'Furniture', 'Office Equipment', 'Safety Equipment', 'Hardware', 'Software', 'Services', 'Other'];
        $paymentMethods = ['Cash', 'Bank Transfer', 'Cheque', 'bKash', 'Nagad', 'Rocket'];

        return view('purchases.index', compact('purchases', 'metrics', 'categories', 'paymentMethods'));
    }

    public function store(PurchaseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('invoice_attachment')) {
            $data['invoice_attachment'] = $request->file('invoice_attachment')->store('purchases/invoices', 'public');
        }

        Purchase::create($data);

        return response()->json(['success' => true, 'message' => 'Purchase requisition filed successfully.']);
    }

    public function show(Purchase $purchase)
    {
        return response()->json([
            'success'   => true,
            'data'      => $purchase,
            'p_date'    => $purchase->purchase_date->format('M d, Y'),
            'd_date'    => $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('M d, Y') : 'N/A',
            'subtotal'  => number_format($purchase->quantity * $purchase->unit_price, 2),
            'discount_f'=> number_format($purchase->discount, 2),
            'tax_f'     => number_format($purchase->tax, 2),
            'total_f'   => number_format($purchase->total_amount, 2),
            'created'   => $purchase->created_at->format('M d, Y h:i A')
        ]);
    }

    public function edit(Purchase $purchase)
    {
        return response()->json(['success' => true, 'data' => $purchase]);
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $data = $request->validated();

        if ($request->hasFile('invoice_attachment')) {
            if ($purchase->invoice_attachment) {
                Storage::disk('public')->delete($purchase->invoice_attachment);
            }
            $data['invoice_attachment'] = $request->file('invoice_attachment')->store('purchases/invoices', 'public');
        }

        $purchase->update($data);

        return response()->json(['success' => true, 'message' => 'Procurement tracking ledger record modified successfully.']);
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return response()->json(['success' => true, 'message' => 'Purchase ledger safely archived via Soft Delete.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Purchase::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Batch processing complete: Target lines archived.']);
        }
        return response()->json(['success' => false, 'message' => 'No active identifiers selected.'], 400);
    }

    public function exportCsv()
    {
        $records = Purchase::all();
        $fileName = 'procurement_ledger_' . date('Ymd_His') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['PO Number', 'Date', 'Vendor', 'Project', 'Item', 'Qty', 'Unit', 'Price', 'Total', 'Payment', 'Status']);

            foreach ($records as $row) {
                fputcsv($file, [
                    $row->purchase_order_no,
                    $row->purchase_date->format('Y-m-d'),
                    $row->vendor_name,
                    $row->project_name,
                    $row->item_name,
                    $row->quantity,
                    $row->unit,
                    $row->unit_price,
                    $row->total_amount,
                    $row->payment_status,
                    $row->purchase_status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
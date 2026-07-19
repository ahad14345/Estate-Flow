@extends('layouts.app')

@section('page_title', 'Procurement & Purchase Management - EstateFlow ERP')

@section('content')
<div class="container-fluid px-2 py-3" id="purchase-module-root">
    
    <!-- ERP Statistical Dashboard Block Matrix -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-primary border-4 hover-lift bg-white">
                <div class="text-muted text-xs uppercase font-bold tracking-wider">Aggregate Purchases</div>
                <div class="fs-3 font-bold text-dark mt-1">{{ $metrics['total_count'] }}</div>
                <div class="text-xs text-muted mt-1">Sourced from <span class="font-bold text-primary">{{ $metrics['total_vendors'] }}</span> active suppliers</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-warning border-4 hover-lift bg-white">
                <div class="text-muted text-xs uppercase font-bold tracking-wider">Pending Requisitions</div>
                <div class="fs-3 font-bold text-warning mt-1">{{ $metrics['pending_count'] }}</div>
                <div class="text-xs text-muted mt-1">Awaiting physical receiving verification</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-success border-4 hover-lift bg-white">
                <div class="text-muted text-xs uppercase font-bold tracking-wider">Completed Deliveries</div>
                <div class="fs-3 font-bold text-success mt-1">{{ $metrics['completed_count'] }}</div>
                <div class="text-xs text-muted mt-1">Stock elements successfully cleared</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-info border-4 hover-lift bg-white">
                <div class="text-muted text-xs uppercase font-bold tracking-wider">Monthly Spend Allocation</div>
                <div class="fs-3 font-bold text-info mt-1">${{ number_format($metrics['monthly_amount'], 2) }}</div>
                <div class="text-xs text-muted mt-1">Calculated for current fiscal month billing</div>
            </div>
        </div>
    </div>

    <!-- ERP Filtering Panel Grid Element -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('purchases.index') }}" method="GET" id="erp-purchase-filter-form">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Search Query</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0 text-sm" placeholder="PO Number, Item Name, Staff...">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Project Site</label>
                        <input type="text" name="project_name" value="{{ request('project_name') }}" class="form-control bg-light text-sm" placeholder="e.g. Mirza Tower">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Category</label>
                        <select name="category" class="form-select bg-light text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Payment Status</label>
                        <select name="payment_status" class="form-select bg-light text-sm">
                            <option value="">All Payments</option>
                            <option value="Paid" {{ request('payment_status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Partial" {{ request('payment_status') === 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Unpaid" {{ request('payment_status') === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Procurement State</label>
                        <select name="purchase_status" class="form-select bg-light text-sm">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('purchase_status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Ordered" {{ request('purchase_status') === 'Ordered' ? 'selected' : '' }}>Ordered</option>
                            <option value="Delivered" {{ request('purchase_status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ request('purchase_status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Range Interceptors -->
                    <div class="col-6 col-md-3">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Date Range Boundary</label>
                        <div class="input-group">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control bg-light text-xs">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control bg-light text-xs">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Amount Filter ($ Min / Max)</label>
                        <div class="input-group">
                            <input type="number" name="min_amount" value="{{ request('min_amount') }}" class="form-control bg-light text-xs" placeholder="Min">
                            <input type="number" name="max_amount" value="{{ request('max_amount') }}" class="form-control bg-light text-xs" placeholder="Max">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Order Matrix</label>
                        <select name="sort_by" class="form-select bg-light text-sm">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Entry Timeline</option>
                            <option value="purchase_date" {{ request('sort_by') === 'purchase_date' ? 'selected' : '' }}>Purchase Anniversary</option>
                            <option value="total_amount" {{ request('sort_by') === 'total_amount' ? 'selected' : '' }}>Fiscal Cost</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-1">
                        <label class="form-label text-xs font-bold text-secondary uppercase">Rows</label>
                        <select name="per_page" class="form-select bg-light text-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-end justify-content-end gap-2">
                        <a href="{{ route('purchases.index') }}" class="btn btn-light border text-secondary text-sm px-3 py-2 w-50">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary bg-indigo border-0 text-white text-sm px-3 py-2 w-50 shadow-sm">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Records Matrix Card Layout Container -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0 font-bold text-dark d-flex align-items-center">
                <i class="bi bi-cart-check-fill text-primary me-2"></i> Purchase Requisitions Ledger
            </h5>
            <div class="d-flex flex-wrap gap-2">
                <button id="bulk-delete-action-btn" class="btn btn-outline-danger btn-sm d-none" onclick="fireBulkDelete()">
                    <i class="bi bi-trash3-fill"></i> Mass Delete (<span id="checked-row-counter">0</span>)
                </button>
                <a href="{{ route('purchases.export.csv') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button class="btn btn-primary bg-indigo border-0 btn-sm text-white px-3 shadow-sm" onclick="openCreatePurchaseModal()">
                    <i class="bi bi-plus-lg"></i> Record Purchase Line
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0 text-sm">
                <thead class="table-light border-bottom text-muted text-xs uppercase font-bold">
                    <tr>
                        <th class="ps-4" style="width: 40px;"><input type="checkbox" class="form-check-input" id="check-all-purchase-rows"></th>
                        <th>PO Code</th>
                        <th>Date</th>
                        <th>Supplier / Contractor</th>
                        <th>Project Dest. / Category</th>
                        <th>Item Description Spec</th>
                        <th>Total Cost Valuation</th>
                        <th>State</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $item)
                        <tr class="transition-colors">
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input purchase-row-checkbox" value="{{ $item->id }}">
                            </td>
                            <td class="font-bold text-secondary">{{ $item->purchase_order_no }}</td>
                            <td class="text-secondary">{{ $item->purchase_date->format('M d, Y') }}</td>
                            <td>
                                <div class="font-bold text-dark">{{ $item->vendor_name }}</div>
                                <div class="text-muted text-xs">{{ $item->contractor_name ?? 'Direct Purchase Placement' }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-primary">{{ $item->project_name }}</div>
                                <div class="text-muted text-xs text-slate-400">{{ $item->category }}</div>
                            </td>
                            <td>
                                <div class="text-dark font-medium">{{ $item->item_name }}</div>
                                <div class="text-muted text-xs">{{ number_format($item->quantity, 1) }} {{ $item->unit }} &bull; ${{ number_format($item->unit_price, 2) }}/unit</div>
                            </td>
                            <td class="font-bold text-dark">${{ number_format($item->total_amount, 2) }}</td>
                            <td>
                                <div class="mb-1">
                                    <span class="badge rounded-pill px-2 py-0.5 text-xs border
                                        {{ $item->payment_status === 'Paid' ? 'bg-success-subtle text-success border-success-200' : '' }}
                                        {{ $item->payment_status === 'Partial' ? 'bg-warning-subtle text-warning border-warning-200' : '' }}
                                        {{ $item->payment_status === 'Unpaid' ? 'bg-danger-subtle text-danger border-danger-200' : '' }}
                                    ">
                                        {{ $item->payment_status }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark text-xs border rounded-pill px-2 py-0.5">{{ $item->purchase_status }}</span>
                                </div>
                            </td>
                            <td class="text-end pe-4 whitespace-nowrap space-x-1">
                                <button onclick="viewPurchaseDetails({{ $item->id }})" class="btn btn-light btn-sm border text-primary" title="Invoice Printout View"><i class="bi bi-receipt"></i></button>
                                <button onclick="editPurchaseRecord({{ $item->id }})" class="btn btn-light btn-sm border text-warning" title="Modify Valuation Rules"><i class="bi bi-pencil-square"></i></button>
                                <button onclick="deletePurchaseRecord({{ $item->id }})" class="btn btn-light btn-sm border text-danger" title="Soft Archive Operation"><i class="bi bi-trash3"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-5 text-muted">
                                <i class="bi bi-cart text-3xl text-gray-200 d-block mb-2"></i> No matching procurement ledger lines tracked.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top p-4">
            {{ $purchases->links() }}
        </div>
    </div>
</div>

<!-- Interactive Requisition Operations Entry Modal Form Framework -->
<div class="modal fade" id="purchaseFormModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-bold" id="purchaseModalFormTitle">Create Purchase Order Line Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger d-none" id="purchase-validation-alert-box"></div>
                <form id="purchase-data-entry-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="purchase-action-method" name="_method" value="POST">
                    <input type="hidden" id="purchase-target-id">
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Vendor / Supplier Enterprise Name <span class="text-danger">*</span></label>
                            <input type="text" name="vendor_name" id="field_vendor_name" class="form-control" placeholder="e.g. Bashundhara Cement" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Contractor / Assignee Name (Optional)</label>
                            <input type="text" name="contractor_name" id="field_contractor_name" class="form-control" placeholder="e.g. Mirza Engineers">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Target Real Estate Project Destination <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" id="field_project_name" class="form-control" placeholder="e.g. EstateFlow Premium Residential" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Procurement Material Category <span class="text-danger">*</span></label>
                            <select name="category" id="field_category" class="form-select" required>
                                <option value="">Select...</option>
                                @foreach($categories as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-8">
                            <label class="form-label small font-semibold">Material Item Name Designation <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" id="field_item_name" class="form-control" placeholder="e.g. Portland Composite Cement (Grade 42.5N)" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small font-semibold">Unit Measurement Definition <span class="text-danger">*</span></label>
                            <select name="unit" id="field_unit" class="form-select" required>
                                <option value="Bag">Bag</option>
                                <option value="Piece">Piece</option>
                                <option value="Ton">Ton</option>
                                <option value="Kg">Kg</option>
                                <option value="Meter">Meter</option>
                                <option value="Box">Box</option>
                                <option value="Liter">Liter</option>
                            </select>
                        </div>
                        
                        <!-- Real-time Mathematical Calculation Core Fields Matrix Grid -->
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity" id="field_quantity" class="form-control calc-trigger" required value="0">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Unit Net Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" id="field_unit_price" class="form-control calc-trigger" required value="0">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Tax Evaluation ($)</label>
                            <input type="number" step="0.01" name="tax" id="field_tax" class="form-control calc-trigger" value="0">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Discount Reduction ($)</label>
                            <input type="number" step="0.01" name="discount" id="field_discount" class="form-control calc-trigger" value="0">
                        </div>

                        <!-- Automatically Calculated Final Output Shield Field Indicator -->
                        <div class="col-12 bg-light border p-3 rounded d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-bold text-dark d-block">Automatic Financial Summary Verification Matrix</span>
                                <small class="text-muted text-xs">Calculated automatically via Formula: (Qty &times; Price) + Tax - Discount</small>
                            </div>
                            <div class="fs-4 font-bold text-primary">$<span id="automatic-live-total-display">0.00</span></div>
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" id="field_purchase_date" class="form-control" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Expected Delivery Date</label>
                            <input type="date" name="expected_delivery_date" id="field_expected_delivery_date" class="form-control">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Payment Method Option <span class="text-danger">*</span></label>
                            <select name="payment_method" id="field_payment_method" class="form-select" required>
                                @foreach($paymentMethods as $pm) <option value="{{ $pm }}">{{ $pm }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small font-semibold">Payment Clearing State <span class="text-danger">*</span></label>
                            <select name="payment_status" id="field_payment_status" class="form-select" required>
                                <option value="Unpaid">Unpaid</option>
                                <option value="Partial">Partial</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Verification Audit Operator Signature <span class="text-danger">*</span></label>
                            <input type="text" name="created_by" id="field_created_by" class="form-control" placeholder="Employee Name / Verification Stamp Code" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small font-semibold">Procurement Stage Flow State <span class="text-danger">*</span></label>
                            <select name="purchase_status" id="field_purchase_status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Ordered">Ordered</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small font-semibold">Upload Digital Invoice / Attachment Spec (PDF/JPG/PNG)</label>
                            <input type="file" name="invoice_attachment" id="field_invoice_attachment" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small font-semibold">Administrative Execution Remarks</label>
                            <textarea name="remarks" id="field_remarks" class="form-control" rows="2" placeholder="Describe unique tracking properties here..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-secondary text-sm px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary bg-indigo border-0 text-white text-sm px-4" onclick="commitPurchaseDataEntryForm()">Commit Transaction</button>
            </div>
        </div>
    </div>
</div>

<!-- Highly Stylized Dynamic Invoice Document Details Presentation View Modal Overlay -->
<div class="modal fade" id="purchaseInvoicePrintoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-5" id="printable-invoice-dom-wrapper">
                <!-- Branding Header Container Area -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
                    <div>
                        <h3 class="font-bold text-primary mb-0 tracking-tight">ESTATEFLOW ERP</h3>
                        <small class="text-muted uppercase font-semibold text-xs tracking-wider">Enterprise Resource Requisition Sheet</small>
                    </div>
                    <div class="text-end">
                        <h4 class="font-bold text-dark mb-1" id="inv-po-number"></h4>
                        <span class="text-muted text-xs">Issue Anniversary: <strong id="inv-date" class="text-dark"></strong></span>
                    </div>
                </div>

                <!-- Transaction Summary Matrix Stakeholders -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <span class="text-muted d-block text-xs uppercase font-bold tracking-wider mb-1">Vendor Entity Profile</span>
                        <h5 class="font-bold text-dark mb-0" id="inv-vendor-name"></h5>
                        <small class="text-muted text-xs d-block">Authorized Field Contract Scope Anchor:</small>
                        <span id="inv-contractor-name" class="font-medium text-secondary text-sm"></span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block text-xs uppercase font-bold tracking-wider mb-1">Project Site Mapping</span>
                        <h5 class="font-bold text-primary mb-0" id="inv-project-name"></h5>
                        <small class="text-muted text-xs d-block">Inventory Core Requisition Category Alignment:</small>
                        <span id="inv-category" class="badge bg-light text-dark border text-xs"></span>
                    </div>
                </div>

                <!-- Product Specifications Line Table Elements -->
                <table class="table border text-sm align-middle mb-4">
                    <thead class="table-light font-bold text-xs uppercase text-secondary">
                        <tr>
                            <th>Requested Item Component Specification Line</th>
                            <th class="text-center" style="width: 100px;">Quantity</th>
                            <th class="text-end" style="width: 120px;">Unit Valuation</th>
                            <th class="text-end" style="width: 140px;">Subtotal Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong id="inv-item-name" class="text-dark"></strong>
                                <p id="inv-item-desc" class="text-muted text-xs mb-0 mt-1"></p>
                            </td>
                            <td class="text-center font-semibold text-dark" id="inv-item-qty-unit"></td>
                            <td class="text-end text-dark" id="inv-item-price"></td>
                            <td class="text-end font-semibold text-dark" id="inv-item-subtotal"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Math Calculation Segment Invocations -->
                <div class="row justify-content-end mb-4">
                    <div class="col-5">
                        <div class="d-flex justify-content-between text-sm py-1 border-bottom">
                            <span class="text-muted">Net Base Value:</span>
                            <span class="text-dark" id="inv-calc-base"></span>
                        </div>
                        <div class="d-flex justify-content-between text-sm py-1 border-bottom">
                            <span class="text-muted">Tax Levy Injection (+):</span>
                            <span class="text-danger" id="inv-calc-tax"></span>
                        </div>
                        <div class="d-flex justify-content-between text-sm py-1 border-bottom">
                            <span class="text-muted">Discount Deductions (-):</span>
                            <span class="text-success" id="inv-calc-discount"></span>
                        </div>
                        <div class="d-flex justify-content-between fs-5 font-bold py-2 text-dark">
                            <span>Total Fiscal Value:</span>
                            <span class="text-primary" id="inv-calc-total"></span>
                        </div>
                    </div>
                </div>

                <!-- Workflow Metadata Footer Signatures Block -->
                <div class="bg-light p-3 border rounded mb-4 text-xs">
                    <div class="row">
                        <div class="col-4"><strong>Payment Routing:</strong> <span id="inv-pay-method" class="d-block text-muted"></span></div>
                        <div class="col-4"><strong>Clearing Ledger Code:</strong> <span id="inv-pay-status" class="badge"></span></div>
                        <div class="col-4"><strong>Requisition Status:</strong> <span id="inv-req-status" class="badge bg-dark"></span></div>
                    </div>
                    @if(!empty($item->remarks))
                        <div class="mt-2 pt-2 border-top text-muted"><strong>Requisition Notes:</strong> <span id="inv-remarks-text"></span></div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top text-xs text-muted">
                    <div>Operation Controller Signature: <strong id="inv-created-by" class="text-dark"></strong></div>
                    <button onclick="triggerInvoicePrintExecution()" class="btn btn-primary bg-indigo border-0 text-white btn-sm px-3 d-print-none"><i class="bi bi-printer"></i> Run Hardcopy Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core Interactivity JavaScript Engine Interface Configurations -->
<script>
    let formElementModal, printoutInvoiceModal;

    document.addEventListener("DOMContentLoaded", function() {
        formElementModal = new bootstrap.Modal(document.getElementById('purchaseFormModal'));
        printoutInvoiceModal = new bootstrap.Modal(document.getElementById('purchaseInvoicePrintoutModal'));
        
        setupCalculationAutomationEngine();
        setupDynamicCheckboxSelectors();
    });

    function setupCalculationAutomationEngine() {
        const inputs = document.querySelectorAll('.calc-trigger');
        inputs.forEach(element => {
            element.addEventListener('input', function() {
                const qty = parseFloat(document.getElementById('field_quantity').value) || 0;
                const price = parseFloat(document.getElementById('field_unit_price').value) || 0;
                const tax = parseFloat(document.getElementById('field_tax').value) || 0;
                const discount = parseFloat(document.getElementById('field_discount').value) || 0;
                
                // Pure Formula Implementation: (Quantity * Price) + Tax - Discount
                const outTotal = (qty * price) + tax - discount;
                document.getElementById('automatic-live-total-display').textContent = outTotal.toFixed(2);
            });
        });
    }

    function setupDynamicCheckboxSelectors() {
        const masterSelector = document.getElementById('check-all-purchase-rows');
        const rows = document.querySelectorAll('.purchase-row-checkbox');
        const bulkBtn = document.getElementById('bulk-delete-action-btn');
        const textCount = document.getElementById('checked-row-counter');

        if(masterSelector) {
            masterSelector.addEventListener('change', function() {
                rows.forEach(r => r.checked = masterSelector.checked);
                recalculateSelectionInterfaceState();
            });
        }

        rows.forEach(r => r.addEventListener('change', recalculateSelectionInterfaceState));

        function recalculateSelectionInterfaceState() {
            const activeChecked = document.querySelectorAll('.purchase-row-checkbox:checked').length;
            textCount.textContent = activeChecked;
            if(activeChecked > 0) {
                bulkBtn.classList.remove('d-none');
            } else {
                bulkBtn.classList.add('d-none');
            }
        }
    }

    function openCreatePurchaseModal() {
        document.getElementById('purchase-data-entry-form').reset();
        document.getElementById('purchase-validation-alert-box').classList.add('d-none');
        document.getElementById('purchase-action-method').value = 'POST';
        document.getElementById('purchase-target-id').value = '';
        document.getElementById('automatic-live-total-display').textContent = '0.00';
        document.getElementById('purchaseModalFormTitle').textContent = 'Create Purchase Order Line Entry';
        formElementModal.show();
    }

    function viewPurchaseDetails(id) {
        fetch(`/purchases/${id}`)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    const p = res.data;
                    document.getElementById('inv-po-number').textContent = p.purchase_order_no;
                    document.getElementById('inv-date').textContent = res.p_date;
                    document.getElementById('inv-vendor-name').textContent = p.vendor_name;
                    document.getElementById('inv-contractor-name').textContent = p.contractor_name || 'Direct Procurement Sourced';
                    document.getElementById('inv-project-name').textContent = p.project_name;
                    document.getElementById('inv-category').textContent = p.category;
                    document.getElementById('inv-item-name').textContent = p.item_name;
                    document.getElementById('inv-item-desc').textContent = p.item_description || 'No distinct configuration description recorded.';
                    document.getElementById('inv-item-qty-unit').textContent = `${p.quantity} ${p.unit}`;
                    document.getElementById('inv-item-price').textContent = `$${p.unit_price}`;
                    document.getElementById('inv-item-subtotal').textContent = `$${res.subtotal}`;
                    
                    document.getElementById('inv-calc-base').textContent = `$${res.subtotal}`;
                    document.getElementById('inv-calc-tax').textContent = `+$${res.tax_f}`;
                    document.getElementById('inv-calc-discount').textContent = `-$${res.discount_f}`;
                    document.getElementById('inv-calc-total').textContent = `$${res.total_f}`;
                    
                    document.getElementById('inv-pay-method').textContent = p.payment_method;
                    document.getElementById('inv-created-by').textContent = p.created_by;
                    
                    const payStatus = document.getElementById('inv-pay-status');
                    payStatus.textContent = p.payment_status;
                    payStatus.className = 'badge ' + (p.payment_status === 'Paid' ? 'bg-success' : p.payment_status === 'Partial' ? 'bg-warning text-dark' : 'bg-danger');
                    
                    document.getElementById('inv-req-status').textContent = p.purchase_status;
                    printoutInvoiceModal.show();
                }
            });
    }

    function editPurchaseRecord(id) {
        fetch(`/purchases/${id}/edit`)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    openCreatePurchaseModal();
                    const p = res.data;
                    document.getElementById('purchase-action-method').value = 'PUT';
                    document.getElementById('purchase-target-id').value = p.id;
                    document.getElementById('purchaseModalFormTitle').textContent = `Modify Procurement Transaction: ${p.purchase_order_no}`;
                    
                    document.getElementById('field_vendor_name').value = p.vendor_name;
                    document.getElementById('field_contractor_name').value = p.contractor_name || '';
                    document.getElementById('field_project_name').value = p.project_name;
                    document.getElementById('field_category').value = p.category;
                    document.getElementById('field_item_name').value = p.item_name;
                    document.getElementById('field_unit').value = p.unit;
                    document.getElementById('field_quantity').value = p.quantity;
                    document.getElementById('field_unit_price').value = p.unit_price;
                    document.getElementById('field_tax').value = p.tax;
                    document.getElementById('field_discount').value = p.discount;
                    
                    document.getElementById('field_purchase_date').value = p.purchase_date.substring(0, 10);
                    if(p.expected_delivery_date) {
                        document.getElementById('field_expected_delivery_date').value = p.expected_delivery_date.substring(0, 10);
                    }
                    document.getElementById('field_payment_method').value = p.payment_method;
                    document.getElementById('field_payment_status').value = p.payment_status;
                    document.getElementById('field_created_by').value = p.created_by;
                    document.getElementById('field_purchase_status').value = p.purchase_status;
                    document.getElementById('field_remarks').value = p.remarks || '';
                    
                    // Trigger valuation recalculation matrix rendering update
                    document.getElementById('field_quantity').dispatchEvent(new Event('input'));
                }
            });
    }

    function commitPurchaseDataEntryForm() {
        const form = document.getElementById('purchase-data-entry-form');
        const dataPayload = new FormData(form);
        const recordId = document.getElementById('purchase-target-id').value;
        const targetUrl = recordId ? `/purchases/${recordId}` : '/purchases';

        if(document.getElementById('purchase-action-method').value === 'PUT') {
            dataPayload.append('_method', 'PUT');
        }

        fetch(targetUrl, {
            method: 'POST',
            body: dataPayload,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                formElementModal.hide();
                window.location.reload();
            } else {
                const box = document.getElementById('purchase-validation-alert-box');
                box.innerHTML = '';
                box.classList.remove('d-none');
                let lists = '<ul class="mb-0">';
                Object.keys(res.errors).forEach(key => { lists += `<li>${res.errors[key][0]}</li>`; });
                lists += '</ul>';
                box.innerHTML = lists;
            }
        });
    }

    function deletePurchaseRecord(id) {
        if(confirm('Are you entirely sure you want to soft delete this procurement transaction row line?')) {
            fetch(`/purchases/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(res => { if(res.success) window.location.reload(); });
        }
    }

    function fireBulkDelete() {
        const checkedBoxes = Array.from(document.querySelectorAll('.purchase-row-checkbox:checked')).map(cb => cb.value);
        if(checkedBoxes.length > 0 && confirm(`Mass Action: Archive all ${checkedBoxes.length} selected lines?`)) {
            fetch('/purchases/bulk-delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ ids: checkedBoxes })
            })
            .then(res => res.json())
            .then(res => { if(res.success) window.location.reload(); });
        }
    }

    function triggerInvoicePrintExecution() {
        window.print();
    }
</script>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05) !important; }
    .bg-indigo { background-color: #2563EB !important; }
    .text-primary { color: #2563EB !important; }
    .bg-success-subtle { background-color: #DCFCE7 !important; }
    .bg-danger-subtle { background-color: #FEE2E2 !important; }
    .bg-warning-subtle { background-color: #FEF3C7 !important; }
    
    @media print {
        body * { display: none !important; }
        #purchaseInvoicePrintoutModal, #printable-invoice-dom-wrapper, #printable-invoice-dom-wrapper * { display: block !important; }
        #printable-invoice-dom-wrapper { position: absolute; left: 0; top: 0; width: 100%; }
        .d-print-none { display: none !important; }
    }
</style>
@endsection
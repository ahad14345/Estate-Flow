<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerActivity;
use App\Models\CustomerPropertyInterest;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrmController extends Controller
{
    public function dashboard()
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'Active')->count();
        $leads = Lead::count();
        $followUpsDueToday = FollowUp::whereDate('scheduled_at', today())->count();
        $recentActivities = CustomerActivity::latest('occurred_at')->take(8)->get();
        $customers = Customer::latest()->take(8)->get();
        $leads = Lead::latest()->take(8)->get();
        $followUps = FollowUp::with(['customer', 'lead'])->latest('scheduled_at')->take(8)->get();

        $customerStats = Customer::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $leadStats = Lead::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $cityStats = Customer::selectRaw('city, count(*) as total')->whereNotNull('city')->groupBy('city')->pluck('total', 'city');
        $typeStats = Customer::selectRaw('customer_type, count(*) as total')->groupBy('customer_type')->pluck('total', 'customer_type');

        return view('crm.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'leads',
            'followUpsDueToday',
            'recentActivities',
            'customers',
            'leads',
            'followUps',
            'customerStats',
            'leadStats',
            'cityStats',
            'typeStats'
        ));
    }

    public function customersIndex(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        $customers = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('crm.customers.index', compact('customers'));
    }

    public function customersCreate()
    {
        return view('crm.customers.create');
    }

    public function customersStore(CustomerStoreRequest $request)
    {
        $customer = Customer::create([
            'customer_code' => $this->generateCode('CUST'),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'national_id' => $request->national_id,
            'address' => $request->address,
            'city' => $request->city,
            'customer_type' => $request->customer_type,
            'preferred_property_type' => $request->preferred_property_type,
            'budget' => $request->budget,
            'assigned_employee' => $request->assigned_employee,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        CustomerActivity::create([
            'customer_id' => $customer->id,
            'activity_type' => 'Registration',
            'subject' => 'Customer registered',
            'description' => 'Customer was created in the CRM.',
            'performed_by' => auth()->user()?->name ?? 'System',
        ]);

        return redirect()->route('crm.customers.index')->with('success', 'Customer created successfully.');
    }

    public function customersShow(Customer $customer)
    {
        $customer->load(['activities', 'propertyInterests', 'followUps', 'leads']);

        return view('crm.customers.show', compact('customer'));
    }

    public function customersEdit(Customer $customer)
    {
        return view('crm.customers.edit', compact('customer'));
    }

    public function customersUpdate(CustomerUpdateRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        CustomerActivity::create([
            'customer_id' => $customer->id,
            'activity_type' => 'Updated',
            'subject' => 'Customer profile updated',
            'description' => 'Customer profile was updated.',
            'performed_by' => auth()->user()?->name ?? 'System',
        ]);

        return redirect()->route('crm.customers.show', $customer)->with('success', 'Customer updated successfully.');
    }

    public function customersDestroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('crm.customers.index')->with('success', 'Customer moved to trash.');
    }

    public function leadsIndex(Request $request)
    {
        $query = Lead::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('crm.leads.index', compact('leads'));
    }

    public function leadsCreate()
    {
        return view('crm.leads.create');
    }

    public function leadsStore(LeadStoreRequest $request)
    {
        $lead = Lead::create([
            'lead_code' => $this->generateCode('LEAD'),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'lead_source' => $request->lead_source,
            'priority' => $request->priority,
            'status' => $request->status,
            'assigned_employee' => $request->assigned_employee,
            'budget' => $request->budget,
            'notes' => $request->notes,
        ]);

        CustomerActivity::create([
            'customer_id' => $lead->customer_id ?? 0,
            'activity_type' => 'Lead',
            'subject' => 'Lead created',
            'description' => 'A new lead was created.',
            'performed_by' => auth()->user()?->name ?? 'System',
        ]);

        return redirect()->route('crm.leads.index')->with('success', 'Lead created successfully.');
    }

    public function leadsShow(Lead $lead)
    {
        $lead->load(['followUps', 'customer']);

        return view('crm.leads.show', compact('lead'));
    }

    public function leadsEdit(Lead $lead)
    {
        return view('crm.leads.edit', compact('lead'));
    }

    public function leadsUpdate(LeadUpdateRequest $request, Lead $lead)
    {
        $lead->update($request->validated());

        if ($request->status === 'Converted' && empty($lead->customer_id)) {
            $customer = Customer::create([
                'customer_code' => $this->generateCode('CUST'),
                'full_name' => $lead->full_name,
                'email' => $lead->email,
                'phone_number' => $lead->phone_number,
                'address' => null,
                'city' => null,
                'customer_type' => 'Buyer',
                'preferred_property_type' => null,
                'budget' => $lead->budget,
                'assigned_employee' => $lead->assigned_employee,
                'status' => 'Active',
                'notes' => $lead->notes,
            ]);

            $lead->update(['customer_id' => $customer->id, 'converted_at' => now()]);

            CustomerActivity::create([
                'customer_id' => $customer->id,
                'activity_type' => 'Lead Conversion',
                'subject' => 'Lead converted to customer',
                'description' => 'Lead was converted to a new customer.',
                'performed_by' => auth()->user()?->name ?? 'System',
            ]);
        }

        return redirect()->route('crm.leads.show', $lead)->with('success', 'Lead updated successfully.');
    }

    public function leadsDestroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('crm.leads.index')->with('success', 'Lead deleted successfully.');
    }

    public function followUpsIndex(Request $request)
    {
        $followUps = FollowUp::with(['customer', 'lead'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('crm.follow-ups.index', compact('followUps'));
    }

    public function followUpsCreate()
    {
        $customers = Customer::all();
        $leads = Lead::all();

        return view('crm.follow-ups.create', compact('customers', 'leads'));
    }

    public function followUpsStore(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'follow_up_type' => ['required', 'in:Call,Meeting,Email,Site Visit'],
            'subject' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'scheduled_at' => ['required', 'date'],
            'reminder_status' => ['nullable', 'in:Pending,Sent,Completed'],
            'status' => ['nullable', 'in:Pending,Completed'],
        ]);

        $followUp = FollowUp::create($data);

        CustomerActivity::create([
            'customer_id' => $followUp->customer_id ?? 0,
            'activity_type' => 'Follow-up',
            'subject' => $followUp->subject,
            'description' => 'A follow-up was scheduled.',
            'performed_by' => auth()->user()?->name ?? 'System',
        ]);

        return redirect()->route('crm.follow-ups.index')->with('success', 'Follow-up scheduled successfully.');
    }

    public function followUpsEdit(FollowUp $followUp)
    {
        $customers = Customer::all();
        $leads = Lead::all();

        return view('crm.follow-ups.edit', compact('followUp', 'customers', 'leads'));
    }

    public function followUpsUpdate(Request $request, FollowUp $followUp)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'follow_up_type' => ['required', 'in:Call,Meeting,Email,Site Visit'],
            'subject' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'scheduled_at' => ['required', 'date'],
            'reminder_status' => ['nullable', 'in:Pending,Sent,Completed'],
            'status' => ['nullable', 'in:Pending,Completed'],
        ]);

        $followUp->update($data);

        return redirect()->route('crm.follow-ups.index')->with('success', 'Follow-up updated successfully.');
    }

    public function followUpsDestroy(FollowUp $followUp)
    {
        $followUp->delete();

        return redirect()->route('crm.follow-ups.index')->with('success', 'Follow-up deleted successfully.');
    }

    public function propertyInterestsIndex(Customer $customer)
    {
        return view('crm.customers.interests', compact('customer'));
    }

    public function propertyInterestsStore(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'property_name' => ['required', 'string', 'max:100'],
            'property_reference' => ['nullable', 'string', 'max:100'],
            'interest_level' => ['required', 'in:Low,Medium,High'],
            'visit_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        CustomerPropertyInterest::create(array_merge($data, ['customer_id' => $customer->id]));

        return back()->with('success', 'Property interest added.');
    }

    public function reports()
    {
        return view('crm.reports');
    }

    protected function generateCode(string $prefix): string
    {
        return $prefix . '-' . strtoupper(Str::random(6));
    }
}

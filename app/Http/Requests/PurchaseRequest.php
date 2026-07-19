<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:purchase_date',
            'vendor_name' => 'required|string|max:150',
            'contractor_name' => 'nullable|string|max:150',
            'project_name' => 'required|string|max:150',
            'category' => 'required|string|in:Construction Materials,Electrical Materials,Plumbing Materials,Interior Materials,Furniture,Office Equipment,Safety Equipment,Hardware,Software,Services,Other',
            'item_name' => 'required|string|max:200',
            'item_description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|in:Bag,Piece,Ton,Kg,Meter,Box,Liter',
            'unit_price' => 'required|numeric|min:0.01',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Bank Transfer,Cheque,bKash,Nagad,Rocket',
            'payment_status' => 'required|string|in:Paid,Partial,Unpaid',
            'purchase_status' => 'required|string|in:Pending,Ordered,Delivered,Cancelled',
            'invoice_attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'remarks' => 'nullable|string|max:1000',
            'created_by' => 'required|string|max:100'
        ];
    }
}
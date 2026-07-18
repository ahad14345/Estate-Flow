<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:customers,email,' . $this->route('customer')->id],
            'phone_number' => ['required', 'string', 'max:30', 'unique:customers,phone_number,' . $this->route('customer')->id],
            'national_id' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'customer_type' => ['required', 'in:Buyer,Seller,Investor,Tenant'],
            'preferred_property_type' => ['nullable', 'string', 'max:100'],
            'budget' => ['nullable', 'numeric'],
            'assigned_employee' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:Lead,Active,Closed'],
            'notes' => ['nullable', 'string'],
        ];
    }
}

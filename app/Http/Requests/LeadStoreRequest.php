<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:leads,email'],
            'phone_number' => ['required', 'string', 'max:30', 'unique:leads,phone_number'],
            'lead_source' => ['required', 'in:Website,Facebook,Referral,Walk-in,Phone Call'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'status' => ['required', 'in:New,Contacted,Interested,Negotiation,Converted,Lost'],
            'assigned_employee' => ['nullable', 'string', 'max:100'],
            'budget' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ];
    }
}

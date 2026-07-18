<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:leads,email,' . $this->route('lead')->id],
            'phone_number' => ['required', 'string', 'max:30', 'unique:leads,phone_number,' . $this->route('lead')->id],
            'lead_source' => ['required', 'in:Website,Facebook,Referral,Walk-in,Phone Call'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'status' => ['required', 'in:New,Contacted,Interested,Negotiation,Converted,Lost'],
            'assigned_employee' => ['nullable', 'string', 'max:100'],
            'budget' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vendorId = $this->route('vendor') ? $this->route('vendor')->id : 'NULL';

        return [
            'company_name' => 'required|string|max:150',
            'contact_person' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'email' => 'required|email|max:100|unique:vendors,email,' . $vendorId . ',id,deleted_at,NULL',
            'biz_reg_no' => 'nullable|string|max:50',
            'tax_vat_no' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:150',
            'biz_category' => 'required|string|max:50',
            'mat_category' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_acc_no' => 'nullable|string|max:50',
            'pay_method' => 'nullable|string|max:50',
            'pay_terms' => 'nullable|string|max:50',
            'status' => 'required|in:Active,Inactive',
            'rating' => 'nullable|integer|min:1|max:50',
            'notes' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trade_license' => 'nullable|mimes:pdf,jpeg,png,jpg|max:5120',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBillingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'rent_id' => 'required|exists:rents,id',
            'billing_month' => 'required|integer|between:1,12',
            'billing_year' => 'required|integer|min:2024',
            'rent_amount' => 'required|numeric|min:0|max:999999999.99',
            'electricity_cost' => 'nullable|numeric|min:0|max:999999999.99',
            'water_cost' => 'nullable|numeric|min:0|max:999999999.99',
            'maintenance_cost' => 'nullable|numeric|min:0|max:999999999.99',
            'other_costs' => 'nullable|numeric|min:0|max:999999999.99',
            'other_costs_description' => 'nullable|string|max:500',
            'discount' => 'nullable|numeric|min:0|max:999999999.99',
            'due_date' => 'required|date|after_or_equal:today',
            'admin_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'rent_id.required' => 'Penyewa harus dipilih',
            'rent_id.exists' => 'Data penyewa tidak valid',
            'billing_month.required' => 'Bulan tagihan harus dipilih',
            'billing_year.required' => 'Tahun tagihan harus diisi',
            'rent_amount.required' => 'Biaya sewa harus diisi',
            'due_date.required' => 'Tanggal jatuh tempo harus diisi',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo minimal hari ini',
        ];
    }
}
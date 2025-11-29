<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKosInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'general_facilities' => 'nullable|array',
            'rules' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'checkin_time' => 'required|date_format:H:i',
            'checkout_time' => 'required|date_format:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kos wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
        ];
    }
}

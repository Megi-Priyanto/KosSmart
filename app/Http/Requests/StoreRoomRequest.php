<?php
// app/Http/Requests/StoreRoomRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'floor' => 'required|string|max:50',
            'type' => 'required|in:putra,putri,campur',
            'capacity' => 'required|integer|min:1|max:10',
            'size' => 'nullable|numeric|min:0',
            'has_window' => 'boolean',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'facilities' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'status' => 'required|in:available,occupied,maintenance',
            'last_maintenance' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'room_number.required' => 'Nomor kamar wajib diisi',
            'room_number.unique' => 'Nomor kamar sudah digunakan',
            'type.required' => 'Tipe kamar wajib dipilih',
            'price.required' => 'Harga sewa wajib diisi',
        ];
    }
}
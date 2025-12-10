<?php
// app/Http/Requests/UpdateRoomRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin'); 
    }

    public function rules(): array
    {
        return [
            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')->ignore($this->route('room'))
            ],
            'floor' => 'required|string|max:50',
            'type' => 'required|in:putra,putri,campur',
            'capacity' => 'required|integer|min:1|max:10',
            'size' => 'nullable|numeric|min:0',
            'has_window' => 'boolean',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            
            // Multiple images validation
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            
            // For removing images
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            
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
            'images.max' => 'Maksimal 10 gambar yang dapat diupload',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'images.*.max' => 'Ukuran gambar maksimal 5MB per file',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'has_window' => $this->has('has_window') ? true : false,
        ]);
    }
}
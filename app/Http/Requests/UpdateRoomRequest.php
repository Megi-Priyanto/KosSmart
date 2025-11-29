<?php

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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'status' => 'required|in:available,occupied,maintenance',
            'last_maintenance' => 'nullable|date',
            'remove_images' => 'nullable|array',
        ];
    }
}
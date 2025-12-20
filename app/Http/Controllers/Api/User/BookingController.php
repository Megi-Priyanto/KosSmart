<?php
// app/Http/Controllers/Api/User/BookingController.php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Room;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookingController extends BaseApiController
{
    /**
     * Create Booking
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'start_date' => 'required|date|after_or_equal:today',
                'deposit_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $room = Room::findOrFail($validated['room_id']);

            // Cek ketersediaan
            if (!$room->isAvailable()) {
                return $this->errorResponse('Kamar tidak tersedia');
            }

            // Cek booking existing
            $existingRent = Rent::where('user_id', $request->user()->id)
                ->whereIn('status', ['pending', 'active'])
                ->exists();

            if ($existingRent) {
                return $this->errorResponse('Anda sudah memiliki booking aktif');
            }

            DB::beginTransaction();

            // Upload bukti
            $depositProofPath = $request->file('deposit_proof')
                ->store('deposits', 'public');

            // Hitung DP
            $depositAmount = $room->price * 0.5;

            // Buat rent
            $rent = Rent::create([
                'user_id' => $request->user()->id,
                'room_id' => $room->id,
                'start_date' => $validated['start_date'],
                'end_date' => null,
                'deposit_paid' => $depositAmount,
                'monthly_rent' => $room->price,
                'status' => 'pending',
                'notes' => 'Bukti DP: ' . $depositProofPath,
            ]);

            DB::commit();

            return $this->successResponse([
                'booking' => [
                    'id' => $rent->id,
                    'room_number' => $room->room_number,
                    'start_date' => $rent->start_date,
                    'deposit_paid' => $rent->deposit_paid,
                    'monthly_rent' => $rent->monthly_rent,
                    'status' => $rent->status,
                ],
            ], 'Booking berhasil! Menunggu konfirmasi admin.', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($depositProofPath)) {
                Storage::disk('public')->delete($depositProofPath);
            }

            return $this->errorResponse('Gagal membuat booking: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get User's Bookings
     */
    public function index(Request $request)
    {
        $bookings = Rent::with('room')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(function ($rent) {
                return [
                    'id' => $rent->id,
                    'room_number' => $rent->room->room_number,
                    'start_date' => $rent->start_date,
                    'end_date' => $rent->end_date,
                    'deposit_paid' => $rent->deposit_paid,
                    'monthly_rent' => $rent->monthly_rent,
                    'status' => $rent->status,
                    'created_at' => $rent->created_at,
                ];
            });

        return $this->successResponse(['bookings' => $bookings]);
    }

    /**
     * Get Booking Detail
     */
    public function show($id, Request $request)
    {
        $rent = Rent::with('room.kosInfo')->find($id);

        if (!$rent || $rent->user_id !== $request->user()->id) {
            return $this->notFoundResponse('Booking tidak ditemukan');
        }

        return $this->successResponse([
            'booking' => [
                'id' => $rent->id,
                'room' => [
                    'room_number' => $rent->room->room_number,
                    'type' => $rent->room->type,
                    'price' => $rent->room->price,
                ],
                'start_date' => $rent->start_date,
                'end_date' => $rent->end_date,
                'deposit_paid' => $rent->deposit_paid,
                'monthly_rent' => $rent->monthly_rent,
                'status' => $rent->status,
                'admin_notes' => $rent->admin_notes,
                'created_at' => $rent->created_at,
            ],
        ]);
    }
}
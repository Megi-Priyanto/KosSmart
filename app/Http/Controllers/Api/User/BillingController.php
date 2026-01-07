<?php
// app/Http/Controllers/Api/User/BillingController.php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BillingController extends BaseApiController
{
    /**
     * List User's Billings
     */
    public function index(Request $request)
    {
        $query = Billing::with(['room', 'latestPayment'])
            ->where('user_id', $request->user()->id);

        // Filter status
        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                $query->whereIn('status', ['unpaid', 'pending', 'overdue']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $billings = $query->orderBy('due_date', 'desc')
            ->get()
            ->map(function ($billing) {
                return [
                    'id' => $billing->id,
                    'billing_period' => $billing->formatted_period,
                    'room_number' => $billing->room->room_number,
                    'total_amount' => $billing->total_amount,
                    'due_date' => $billing->due_date->format('Y-m-d'),
                    'status' => $billing->status,
                    'is_overdue' => now()->greaterThan($billing->due_date) && $billing->status !== 'paid',
                ];
            });

        return $this->successResponse(['billings' => $billings]);
    }

    /**
     * Billing Detail
     */
    public function show($id, Request $request)
    {
        $billing = Billing::with(['room', 'rent', 'payments'])->find($id);

        if (!$billing || $billing->user_id !== $request->user()->id) {
            return $this->notFoundResponse('Tagihan tidak ditemukan');
        }

        return $this->successResponse([
            'billing' => [
                'id' => $billing->id,
                'billing_period' => $billing->formatted_period,
                'room_number' => $billing->room->room_number,
                'rent_amount' => $billing->rent_amount,
                'electricity_cost' => $billing->electricity_cost,
                'water_cost' => $billing->water_cost,
                'maintenance_cost' => $billing->maintenance_cost,
                'discount' => $billing->discount,
                'total_amount' => $billing->total_amount,
                'due_date' => $billing->due_date->format('Y-m-d'),
                'status' => $billing->status,
                'admin_notes' => $billing->admin_notes,
                'payments' => $billing->payments->map(fn($p) => [
                    'id' => $p->id,
                    'amount' => $p->amount,
                    'payment_method' => $p->payment_method,
                    'payment_date' => $p->payment_date,
                    'status' => $p->status,
                ]),
            ],
        ]);
    }

    /**
     * Submit Payment
     */
    public function submitPayment(Request $request, $id)
    {
        try {
            $billing = Billing::find($id);

            if (!$billing || $billing->user_id !== $request->user()->id) {
                return $this->notFoundResponse('Tagihan tidak ditemukan');
            }

            if ($billing->status === 'paid') {
                return $this->errorResponse('Tagihan sudah lunas');
            }

            $validated = $request->validate([
                'payment_method' => 'required|in:transfer,cash,e-wallet',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'payment_date' => 'required|date|before_or_equal:today',
                'amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Upload proof
            $proofPath = $request->file('payment_proof')
                ->store('payment-proofs', 'public');

            // Create payment
            Payment::create([
                'user_id' => $request->user()->id,
                'billing_id' => $billing->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_proof' => $proofPath,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            // Update billing
            $billing->markAsPending();

            DB::commit();

            return $this->successResponse(null, 'Bukti pembayaran berhasil dikirim', 201);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            return $this->errorResponse('Gagal mengirim pembayaran', null, 500);
        }
    }
}
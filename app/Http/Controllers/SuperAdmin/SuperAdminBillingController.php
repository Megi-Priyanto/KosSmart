<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminBilling;
use App\Models\TempatKos;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminBillingController extends Controller
{
    /**
     * Display listing of admin billing
     */
    public function index(Request $request)
    {
        $query = AdminBilling::with(['tempatKos', 'admin']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('billing_month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        // Search by tempat kos or admin
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tempatKos', function ($q) use ($search) {
                $q->where('nama_kos', 'like', "%{$search}%");
            })->orWhereHas('admin', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $billings = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => AdminBilling::count(),
            'unpaid' => AdminBilling::unpaid()->count(),
            'overdue' => AdminBilling::overdue()->count(),
            'paid' => AdminBilling::paid()->count(),
        ];

        return view('superadmin.billing.index', compact('billings', 'stats'));
    }

    /**
     * Show form to create billing
     */
    public function create()
    {
        // Get all active tempat kos with their admins
        $tempatKosList = TempatKos::active()
            ->with(['admins' => function ($q) {
                $q->where('role', 'admin');
            }])
            ->get();

        return view('superadmin.billing.create', compact('tempatKosList'));
    }

    /**
     * Store new billing (Generate Massal)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'billing_type' => 'required|in:all,selected',
            'tempat_kos_ids' => 'required_if:billing_type,selected|array',
            'tempat_kos_ids.*' => 'exists:tempat_kos,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'due_date' => 'required|date|after:today',
            'billing_month' => 'required|integer|min:1|max:12',
            'billing_year' => 'required|integer|min:2020',
        ]);

        try {
            DB::beginTransaction();

            // FIX: Ambil tempat kos IDs sesuai tipe
            if ($validated['billing_type'] === 'all') {
                $tempatKosIds = TempatKos::active()->pluck('id')->toArray();
            } else {
                $tempatKosIds = $validated['tempat_kos_ids'];
            }

            $period = sprintf('%04d-%02d', $validated['billing_year'], $validated['billing_month']);
            $created = 0;
            $skipped = 0;
            $errors = [];

            foreach ($tempatKosIds as $tempatKosId) {
                // Cek apakah tempat kos ada
                $tempatKos = TempatKos::find($tempatKosId);
                
                if (!$tempatKos) {
                    $errors[] = "Tempat kos ID {$tempatKosId} tidak ditemukan";
                    $skipped++;
                    continue;
                }

                // FIX: Cari admin dengan benar
                $admin = User::where('tempat_kos_id', $tempatKosId)
                    ->where('role', 'admin')
                    ->first();

                if (!$admin) {
                    $errors[] = "Tempat kos '{$tempatKos->nama_kos}' tidak memiliki admin";
                    $skipped++;
                    continue;
                }

                // Cek apakah tagihan periode ini sudah ada
                $exists = AdminBilling::where('tempat_kos_id', $tempatKosId)
                    ->where('billing_period', $period)
                    ->exists();

                if ($exists) {
                    $errors[] = "Tagihan periode {$period} untuk '{$tempatKos->nama_kos}' sudah ada";
                    $skipped++;
                    continue;
                }

                // Buat tagihan
                $billing = AdminBilling::create([
                    'tempat_kos_id' => $tempatKosId,
                    'admin_id' => $admin->id,
                    'billing_period' => $period,
                    'billing_month' => $validated['billing_month'],
                    'billing_year' => $validated['billing_year'],
                    'amount' => $validated['amount'],
                    'description' => $validated['description'],
                    'due_date' => $validated['due_date'],
                    'status' => 'unpaid',
                ]);

                // Buat notifikasi untuk admin
                Notification::create([
                    'tempat_kos_id' => $tempatKosId,
                    'type' => 'billing',
                    'title' => 'Tagihan Operasional Aplikasi',
                    'message' => "Tagihan operasional sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . " untuk periode " . date('F Y', strtotime($period . '-01')) . " telah diterbitkan. Jatuh tempo: " . date('d M Y', strtotime($validated['due_date'])),
                    'user_id' => $admin->id,
                    'admin_billing_id' => $billing->id,
                    'due_date' => $validated['due_date'],
                    'status' => 'unread',
                ]);

                $created++;
            }

            DB::commit();

            // Pesan sukses yang lebih informatif
            if ($created > 0) {
                $message = "Berhasil membuat {$created} tagihan.";
                if ($skipped > 0) {
                    $message .= " {$skipped} dilewati.";
                }
                
                // Log errors untuk debugging
                if (!empty($errors)) {
                    Log::info('Billing creation errors:', $errors);
                }
                
                return redirect()
                    ->route('superadmin.billing.index')
                    ->with('success', $message);
            } else {
                // Jika tidak ada yang berhasil dibuat
                $errorMessage = "Tidak ada tagihan yang berhasil dibuat. ";
                if (!empty($errors)) {
                    $errorMessage .= "Alasan: " . implode(', ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $errorMessage .= " dan " . (count($errors) - 3) . " lainnya.";
                    }
                }
                
                return back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Billing creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show billing detail
     */
    public function show(AdminBilling $billing)
    {
        $billing->load(['tempatKos', 'admin']);

        return view('superadmin.billing.show', compact('billing'));
    }

    /**
     * Verify payment
     */
    public function verify(Request $request, AdminBilling $billing)
    {
        if ($billing->status !== 'pending') {
            return back()->with('error', 'Tagihan tidak dalam status menunggu verifikasi.');
        }

        try {
            DB::beginTransaction();
            
            $billing->update([
                'status' => 'paid',
                'verified_by' => auth()->id(),
            ]);

            // Mark notifikasi pembayaran dari admin sebagai read (untuk Super Admin)
            Notification::where('admin_billing_id', $billing->id)
                ->where('type', 'payment')
                ->where('status', 'unread')
                ->update(['status' => 'read']);

            // Buat notifikasi baru untuk admin (konfirmasi verifikasi)
            Notification::create([
                'tempat_kos_id' => $billing->tempat_kos_id,
                'type' => 'payment',
                'title' => 'Pembayaran Terverifikasi',
                'message' => "Pembayaran tagihan periode " . date('F Y', strtotime($billing->billing_period . '-01')) . " telah diverifikasi oleh Super Admin.",
                'user_id' => $billing->admin_id,
                'admin_billing_id' => $billing->id,
                'status' => 'unread',
            ]);

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete billing
     */
    public function destroy(AdminBilling $billing)
    {
        try {
            // Hapus semua notifikasi terkait billing ini
            Notification::where('admin_billing_id', $billing->id)->delete();
            
            $billing->delete();

            return redirect()
                ->route('superadmin.billing.index')
                ->with('success', 'Tagihan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
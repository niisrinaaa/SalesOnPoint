<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RobuxTransaction;
use App\Models\RobuxPackage;
use Illuminate\Http\Request;

class RobuxTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = RobuxTransaction::with(['package', 'user']);

        // Filter berdasarkan status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan status delivery
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Search berdasarkan username atau transaction code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username_roblox', 'like', "%{$search}%")
                  ->orWhere('transaction_code', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.robux.transactions.index', compact('transactions'));
    }

    public function show(RobuxTransaction $transaction)
    {
        $transaction->load(['package', 'user']);
        return view('admin.robux.transactions.show', compact('transaction'));
    }

    // Update payment status
    public function updatePaymentStatus(Request $request, RobuxTransaction $transaction)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,cancelled',
            'payment_reference' => 'nullable|string|max:255'
        ]);

        if ($validated['payment_status'] == 'paid') {
            $transaction->markAsPaid($validated['payment_reference']);
        } else {
            $transaction->update($validated);
        }

        return redirect()->back()
            ->with('success', 'Status pembayaran berhasil diupdate!');
    }

    // Update delivery status
    public function updateDeliveryStatus(Request $request, RobuxTransaction $transaction)
    {
        $validated = $request->validate([
            'delivery_status' => 'required|in:waiting,processing,completed,failed'
        ]);

        if ($validated['delivery_status'] == 'completed') {
            $transaction->markAsDelivered();
        } else {
            $transaction->update($validated);
        }

        return redirect()->back()
            ->with('success', 'Status pengiriman berhasil diupdate!');
    }
}

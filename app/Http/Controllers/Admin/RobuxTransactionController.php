<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
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

        $transactions = $query->latest()->paginate(20);

        return view('admin.robux.packages.transactions.index', compact('transactions'));
    }

    public function show(RobuxTransaction $transaction)
    {
        $transaction->load(['package', 'user']);
        return view('admin.robux.packages.transactions.show', compact('transaction'));
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



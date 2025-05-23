<?php

namespace App\Http\Controllers\userguest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RobuxPackage;
use App\Models\RobuxTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class MbohController extends Controller
{
    public function index()
    {
        $packages = RobuxPackage::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('amount', 'asc')
            ->get();

        return view('robux.index', compact('packages'));
    }

    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'username_roblox' => 'required|string|max:255',
            'package_id' => 'nullable|exists:robux_packages,id',
            'custom_amount' => 'nullable|integer|min:100|max:1000000',
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
        ]);

        if ($request->filled('package_id') && $request->filled('custom_amount')) {
            return redirect()->back()
                ->with('error', 'Pilih paket atau masukkan jumlah custom, tidak boleh keduanya.');
        }

        if ($request->filled('package_id')) {
            $package = RobuxPackage::findOrFail($validated['package_id']);
            if ($package->stock <= 0) {
                return redirect()->back()->with('error', 'Stok paket ini habis!');
            }
            $robuxAmount = $package->amount;
            $price = $package->price;
            $packageId = $package->id;
        } elseif ($request->filled('custom_amount')) {
            $robuxAmount = $validated['custom_amount'];
            $rate = 109;
            $price = $robuxAmount * $rate;
            $packageId = null;
        } else {
            return redirect()->back()->with('error', 'Pilih paket atau masukkan jumlah custom!');
        }

        $transaction = RobuxTransaction::create([
            'user_id' => Auth::id(),
            'package_id' => $packageId,
            'username_roblox' => $validated['username_roblox'],
            'robux_amount' => $robuxAmount,
            'price_paid' => $price,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'delivery_status' => 'waiting',
            'transaction_code' => 'RBX-' . strtoupper(Str::random(8)),
        ]);

        if ($packageId) {
            $package->decrement('stock');
        }

        return redirect()->route('robux.payment', $transaction)
            ->with('success', 'Transaksi berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function payment(RobuxTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        return view('robux.payment', compact('transaction'));
    }

    public function confirmPayment(Request $request, RobuxTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'payment_' . $transaction->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            $transaction->update([
                'payment_proof' => $path,
                'payment_status' => 'pending_verification',
            ]);
        }

        return redirect()->route('robux.status', $transaction)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    public function status(RobuxTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('robux.status', compact('transaction'));
    }

    public function getPrice(Request $request)
    {
        $amount = $request->input('amount', 100);
        $rate = 109;
        $price = $amount * $rate;

        return response()->json([
            'price' => number_format($price, 0, ',', '.'),
        ]);
    }
}

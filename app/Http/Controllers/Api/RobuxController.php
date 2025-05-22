<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RobuxPackage;
use App\Models\RobuxTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RobuxController extends Controller
{
    // API untuk frontend - ambil paket yang tersedia
    public function getPackages()
    {
        $packages = RobuxPackage::active()
            ->inStock()
            ->orderBy('amount', 'asc')
            ->get()
            ->map(function($package) {
                return [
                    'id' => $package->id,
                    'amount' => $package->amount,
                    'price' => $package->price,
                    'formatted_price' => $package->formatted_price,
                    'stock' => $package->stock,
                    'description' => $package->description
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    // API untuk buat transaksi
    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'username_roblox' => 'required|string|max:100',
            'package_id' => 'required|exists:robux_packages,id'
        ]);

        DB::beginTransaction();
        try {
            $package = RobuxPackage::find($validated['package_id']);
            
            // Cek stok
            if ($package->stock <= 0) {
                throw new \Exception('Stok tidak tersedia');
            }

            if (!$package->is_active) {
                throw new \Exception('Paket tidak aktif');
            }

            // Kurangi stok
            if (!$package->decrementStock()) {
                throw new \Exception('Gagal mengurangi stok');
            }

            // Buat transaksi
            $transaction = RobuxTransaction::create([
                'username_roblox' => $validated['username_roblox'],
                'package_id' => $package->id,
                'robux_amount' => $package->amount,
                'price_paid' => $package->price,
                'payment_status' => 'pending',
                'delivery_status' => 'waiting'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'transaction_code' => $transaction->transaction_code,
                    'total_price' => $package->formatted_price,
                    'robux_amount' => $package->amount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // API untuk cek status transaksi
    public function checkTransaction($transactionCode)
    {
        $transaction = RobuxTransaction::where('transaction_code', $transactionCode)
            ->with('package')
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transaction_code' => $transaction->transaction_code,
                'username_roblox' => $transaction->username_roblox,
                'robux_amount' => $transaction->robux_amount,
                'price_paid' => number_format($transaction->price_paid, 0, ',', '.'),
                'payment_status' => $transaction->payment_status,
                'delivery_status' => $transaction->delivery_status,
                'created_at' => $transaction->created_at->format('d/m/Y H:i')
            ]
        ]);
    }
}
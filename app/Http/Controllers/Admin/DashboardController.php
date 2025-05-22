<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RobuxTransaction;
use App\Models\RobuxPackage;
use App\Models\StockLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total revenue hari ini
        $todayRevenue = RobuxTransaction::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('price_paid');

        // Total transaksi hari ini
        $todayTransactions = RobuxTransaction::whereDate('created_at', today())->count();

        // Pending deliveries
        $pendingDeliveries = RobuxTransaction::where('delivery_status', 'processing')->count();

        // Low stock packages
        $lowStockPackages = RobuxPackage::where('stock', '<', 10)
            ->where('is_active', true)
            ->get();

        // Total stok semua paket
        $totalStock = RobuxPackage::where('is_active', true)->sum('stock');

        // Recent transactions
        $recentTransactions = RobuxTransaction::with(['package', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $totalRobuxAmount = RobuxPackage::active()->get()->sum(function ($package) {
        return $package->amount * $package->stock;
});

        return view('admin.dashboard', compact([
            'todayRevenue',
            'todayTransactions', 
            'pendingDeliveries',
            'lowStockPackages',
            'totalStock',
            'recentTransactions',
            'totalRobuxAmount'
        ]));
    }
}

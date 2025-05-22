<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RobuxPackage;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RobuxPackageController extends Controller
{
    public function index()
    {
        $packages = RobuxPackage::with(['stockLogs' => function($query) {
            $query->latest()->limit(5);
        }])->get();
        
        return view('admin.robux.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.robux.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $package = RobuxPackage::create($validated);

        // Log initial stock
        if ($package->stock > 0) {
            StockLog::create([
                'package_id' => $package->id,
                'old_stock' => 0,
                'new_stock' => $package->stock,
                'change_amount' => $package->stock,
                'change_type' => 'restock',
                'notes' => 'Initial stock setup',
                'admin_user' => Auth::user()->name
            ]);
        }

        return redirect()->route('admin.robux.packages.index')
            ->with('success', 'Paket Robux berhasil ditambahkan!');
    }

    public function show(RobuxPackage $package)
    {
        $package->load(['stockLogs' => function($query) {
            $query->latest();
        }, 'transactions' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.robux.packages.show', compact('package'));
    }

    public function edit(RobuxPackage $package)
    {
        return view('admin.robux.packages.edit', compact('package'));
    }

    public function update(Request $request, RobuxPackage $package)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $package->update($validated);

        return redirect()->route('admin.robux.packages.index')
            ->with('success', 'Paket Robux berhasil diupdate!');
    }

    public function destroy(RobuxPackage $package)
    {
        // Cek apakah ada transaksi terkait
        if ($package->transactions()->count() > 0) {
            return redirect()->route('admin.robux.packages.index')
                ->with('error', 'Tidak dapat menghapus paket yang sudah memiliki transaksi!');
        }

        $package->delete();

        return redirect()->route('admin.robux.packages.index')
            ->with('success', 'Paket Robux berhasil dihapus!');
    }

    // Method khusus untuk update stock
    public function updateStock(Request $request, RobuxPackage $package)
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $package->updateStock(
            $validated['new_stock'],
            $validated['notes'],
            Auth::user()->name
        );

        return redirect()->back()
            ->with('success', 'Stok berhasil diupdate!');
    }
}

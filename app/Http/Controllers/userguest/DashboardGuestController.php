<?php

namespace App\Http\Controllers\UserGuest;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\RobuxPackage;
use Illuminate\Http\Request;

class DashboardGuestController extends Controller
{
    public function index()
    {
        $packages = RobuxPackage::active()
            ->inStock() 
            ->orderBy('amount', 'asc')
            ->limit(6) // Tampilkan hanya 6 paket untuk quick select
            ->get();

        // Hitung total stok (dalam bentuk robux)
        $totalStock = RobuxPackage::active()
            ->get()
            ->reduce(function ($carry, $package) {
                return $carry + ($package->stock * $package->amount);
            }, 0);

        return view('guest.index', compact('packages', 'totalStock'));
    }
}
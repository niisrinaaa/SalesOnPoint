@extends('admin.layout')
@section('title', 'Dashboard')
@section('content-title', 'Dashboard Robux Top-Up')
@section('content')

<div class="row">
    <!-- Today Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Revenue Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today Transactions Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Transaksi Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayTransactions }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Deliveries Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pending Delivery</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingDeliveries }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Stock Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Stok Robux</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalStock) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Low Stock Alert -->
    @if($lowStockPackages->count() > 0)
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">⚠️ Stok Menipis</h6>
            </div>
            <div class="card-body">
                @foreach($lowStockPackages as $package)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $package->amount }} Robux</span>
                    <span class="badge badge-danger">{{ $package->stock }} tersisa</span>
                </div>
                @endforeach
                <a href="{{ route('admin.robux.packages.index') }}" class="btn btn-sm btn-primary">
                    Kelola Stok
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="col-lg-{{ $lowStockPackages->count() > 0 ? '6' : '12' }} mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
            </div>
            <div class="card-body">
                @forelse($recentTransactions as $transaction)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $transaction->username_roblox }}</strong><br>
                        <small class="text-muted">{{ $transaction->transaction_code }}</small>
                    </div>
                    <div class="text-right">
                        <div>{{ $transaction->robux_amount }} Robux</div>
                        <span class="badge {{ $transaction->payment_status_badge }}">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-muted">Belum ada transaksi</p>
                @endforelse
                
                @if($recentTransactions->count() > 0)
                <a href="{{ route('admin.robux.transactions.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua Transaksi
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

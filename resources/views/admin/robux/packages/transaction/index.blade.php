@extends('admin.layout')
@section('title', 'Transaksi Robux')
@section('content-title', 'Kelola Transaksi Robux')
@section('content')

@session('success')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endsession

<div class="card shadow">
    <div class="card-header py-3">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
            </div>
            <div class="col-auto">
                <!-- Filter Form -->
                <form method="GET" class="d-inline-flex">
                    <select name="payment_status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                        <option value="">Semua Status Pembayaran</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <select name="delivery_status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                        <option value="">Semua Status Delivery</option>
                        <option value="waiting" {{ request('delivery_status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                        <option value="processing" {{ request('delivery_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('delivery_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('delivery_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari username/kode..." value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Username Roblox</th>
                        <th>Paket</th>
                        <th>Harga</th>
                        <th>Status Bayar</th>
                        <th>Status Kirim</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <strong>{{ $transaction->transaction_code }}</strong>
                            @if($transaction->user)
                            <br><small class="text-muted">{{ $transaction->user->name }}</small>
                            @endif
                        </td>
                        <td>{{ $transaction->username_roblox }}</td>
                        <td>
                            {{ number_format($transaction->robux_amount) }} Robux
                            <br><small class="text-muted">{{ $transaction->package->description ?? '' }}</small>
                        </td>
                        <td>Rp {{ number_format($transaction->price_paid, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $transaction->payment_status_badge }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $transaction->delivery_status_badge }}">
                                {{ ucfirst($transaction->delivery_status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.robux.transactions.show', $transaction) }}" 
                               class="btn btn-sm btn-info">Detail</a>
                            
                            @if($transaction->payment_status == 'pending')
                            <button type="button" class="btn btn-sm btn-success" 
                                    data-toggle="modal" data-target="#paymentModal{{ $transaction->id }}">
                                Konfirm Bayar
                            </button>
                            @endif

                            @if($transaction->payment_status == 'paid' && $transaction->delivery_status != 'completed')
                            <button type="button" class="btn btn-sm btn-primary" 
                                    data-toggle="modal" data-target="#deliveryModal{{ $transaction->id }}">
                                Update Kirim
                            </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Payment Status -->
                    <div class="modal fade" id="paymentModal{{ $transaction->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Status Pembayaran</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.robux.transactions.update-payment', $transaction) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Status Pembayaran</label>
                                            <select name="payment_status" class="form-control" required>
                                                <option value="pending" {{ $transaction->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="failed" {{ $transaction->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                                <option value="cancelled" {{ $transaction->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Referensi Pembayaran (Opsional)</label>
                                            <input type="text" name="payment_reference" class="form-control" 
                                                   value="{{ $transaction->payment_reference }}" 
                                                   placeholder="ID transaksi dari payment gateway">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delivery Status -->
                    <div class="modal fade" id="deliveryModal{{ $transaction->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Status Pengiriman</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.robux.transactions.update-delivery', $transaction) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Status Pengiriman</label>
                                            <select name="delivery_status" class="form-control" required>
                                                <option value="waiting" {{ $transaction->delivery_status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                                <option value="processing" {{ $transaction->delivery_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="completed" {{ $transaction->delivery_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="failed" {{ $transaction->delivery_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-info">
                                            <strong>Username Roblox:</strong> {{ $transaction->username_roblox }}<br>
                                            <strong>Jumlah Robux:</strong> {{ number_format($transaction->robux_amount) }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="d-flex justify-content-center">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

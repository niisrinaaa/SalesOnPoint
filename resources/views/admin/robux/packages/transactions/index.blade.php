@extends('admin.layout')
@section('title', 'Transaksi Robux')
@section('content-title', 'Kelola Transaksi Robux')
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Filter & Search -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Transaksi</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.robux.transactions.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_status">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="waiting_confirmation" {{ request('payment_status') == 'waiting_confirmation' ? 'selected' : '' }}>Waiting Confirmation</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="delivery_status">Status Pengiriman</label>
                        <select name="delivery_status" id="delivery_status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="waiting" {{ request('delivery_status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                            <option value="processing" {{ request('delivery_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('delivery_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('delivery_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        <span class="text-muted">Total: {{ $transactions->total() }} transaksi</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Pengguna</th>
                        <th>Username Roblox</th>
                        <th>Paket</th>
                        <th>Total</th>
                        <th>Status Bayar</th>
                        <th>Status Kirim</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <strong>{{ $transaction->transaction_code }}</strong>
                            </td>
                            <td>
                                {{ $transaction->user->name ?? 'Guest' }}<br>
                                <small class="text-muted">{{ $transaction->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                <strong>{{ $transaction->username_roblox }}</strong>
                            </td>
                            <td>
                                @if($transaction->package)
                                    {{ number_format($transaction->package->amount) }} Robux<br>
                                    <small class="text-muted">Package</small>
                                @else
                                    {{ number_format($transaction->robux_amount) }} Robux<br>
                                    <small class="text-muted">Custom</small>
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($transaction->price_paid) }}</strong>
                            </td>
                            <td>
                                @php
                                    $paymentBadgeClass = [
                                        'pending' => 'badge-warning',
                                        'waiting_confirmation' => 'badge-info',
                                        'paid' => 'badge-success',
                                        'failed' => 'badge-danger',
                                        'cancelled' => 'badge-secondary'
                                    ][$transaction->payment_status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $paymentBadgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_status)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $deliveryBadgeClass = [
                                        'waiting' => 'badge-secondary',
                                        'processing' => 'badge-primary',
                                        'completed' => 'badge-success',
                                        'failed' => 'badge-danger'
                                    ][$transaction->delivery_status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $deliveryBadgeClass }}">
                                    {{ ucfirst($transaction->delivery_status) }}
                                </span>
                            </td>
                            <td>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
                                <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.robux.transactions.show', $transaction->transaction_code) }}" 
                                class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($transaction->payment_status == 'waiting_confirmation')
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="updatePaymentStatus({{ $transaction->id }}, 'paid')"
                                            title="Konfirmasi Pembayaran">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="updatePaymentStatus({{ $transaction->id }}, 'failed')"
                                            title="Tolak Pembayaran">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                @if($transaction->payment_status == 'paid' && $transaction->delivery_status == 'waiting')
                                    <button type="button" class="btn btn-primary btn-sm" 
                                            onclick="updateDeliveryStatus({{ $transaction->id }}, 'processing')"
                                            title="Mulai Proses">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif

                                @if($transaction->delivery_status == 'processing')
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="updateDeliveryStatus({{ $transaction->id }}, 'completed')"
                                            title="Selesaikan">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada transaksi ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modal for Status Updates -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalTitle">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="statusModalBody">
                        <!-- Content will be inserted here -->
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

<script>
function updatePaymentStatus(transactionId, status) {
    const modal = $('#statusModal');
    const form = $('#statusForm');
    const title = $('#statusModalTitle');
    const body = $('#statusModalBody');
    
    form.attr('action', `/admin/robux/transactions/${transactionId}/payment-status`);
    
    let statusText = status === 'paid' ? 'Konfirmasi Pembayaran' : 'Tolak Pembayaran';
    let statusColor = status === 'paid' ? 'success' : 'danger';
    
    title.text(statusText);
    
    body.html(`
        <div class="alert alert-${statusColor}">
            <i class="fas fa-exclamation-triangle"></i>
            Apakah Anda yakin ingin ${statusText.toLowerCase()}?
        </div>
        <input type="hidden" name="payment_status" value="${status}">
        <div class="form-group">
            <label for="payment_reference">Referensi Pembayaran (Opsional)</label>
            <input type="text" class="form-control" name="payment_reference" 
                   placeholder="Nomor referensi atau catatan">
        </div>
    `);
    
    modal.modal('show');
}

function updateDeliveryStatus(transactionId, status) {
    const modal = $('#statusModal');
    const form = $('#statusForm');
    const title = $('#statusModalTitle');
    const body = $('#statusModalBody');
    
    form.attr('action', `/admin/robux/transactions/${transactionId}/delivery-status`);
    
    let statusText = status === 'processing' ? 'Mulai Proses Pengiriman' : 'Selesaikan Pengiriman';
    let statusColor = status === 'processing' ? 'primary' : 'success';
    
    title.text(statusText);
    
    body.html(`
        <div class="alert alert-${statusColor}">
            <i class="fas fa-info-circle"></i>
            Apakah Anda yakin ingin ${statusText.toLowerCase()}?
        </div>
        <input type="hidden" name="delivery_status" value="${status}">
    `);
    
    modal.modal('show');
}

// Auto refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>

@endsection
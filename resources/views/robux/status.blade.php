
{{-- resources/views/userpov/status.blade.php --}}
@extends('template')

@section('title', 'Status Transaksi - ' . $transaction->transaction_code)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Transaksi</h5>
                </div>
                <div class="card-body">
                    <!-- Progress Steps -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="progress" style="height: 25px;">
                                @php
                                    $progress = 25;
                                    if($transaction->payment_status == 'paid') $progress = 50;
                                    if($transaction->delivery_status == 'processing') $progress = 75;  
                                    if($transaction->delivery_status == 'completed') $progress = 100;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                                    {{ $progress }}%
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Pending</small>
                                <small>Paid</small>
                                <small>Processing</small>
                                <small>Completed</small>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Info -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Kode Transaksi:</strong><br>
                            <span class="text-primary">{{ $transaction->transaction_code }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Tanggal:</strong><br>
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Username Roblox:</strong><br>
                            {{ $transaction->username_roblox }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Jumlah Robux:</strong><br>
                            {{ number_format($transaction->robux_amount) }} RBX
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Total Pembayaran:</strong><br>
                            <span class="h6 text-success">{{ $transaction->formatted_price }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status Pembayaran:</strong><br>
                            <span class="badge {{ $transaction->status_badge }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status Pengiriman:</strong><br>
                            <span class="badge {{ $transaction->delivery_badge }}">
                                {{ ucfirst($transaction->delivery_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    @if($transaction->payment_status == 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> 
                            Menunggu pembayaran. Silakan lakukan pembayaran dan upload bukti transfer.
                        </div>
                        <a href="{{ route('robux.payment', $transaction->transaction_code) }}" 
                           class="btn btn-primary">Bayar Sekarang</a>
                    @elseif($transaction->payment_status == 'waiting_confirmation')
                        <div class="alert alert-info">
                            <i class="fas fa-hourglass-half"></i> 
                            Bukti pembayaran sedang diverifikasi. Mohon tunggu konfirmasi dari admin.
                        </div>
                    @elseif($transaction->payment_status == 'paid')
                        @if($transaction->delivery_status == 'processing')
                            <div class="alert alert-primary">
                                <i class="fas fa-cog fa-spin"></i> 
                                Pembayaran berhasil! Robux sedang diproses untuk dikirim ke akun Anda.
                            </div>
                        @elseif($transaction->delivery_status == 'completed')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                Selesai! Robux telah berhasil dikirim ke akun {{ $transaction->username_roblox }}.
                            </div>
                        @endif
                    @elseif($transaction->payment_status == 'failed')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> 
                            Pembayaran gagal. Silakan hubungi customer service.
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('robux.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Beli Robux Lagi
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-secondary">
                            <i class="fas fa-sync"></i> Refresh Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh every 30 seconds
setTimeout(function(){
    location.reload();
}, 30000);
</script>
@endsection
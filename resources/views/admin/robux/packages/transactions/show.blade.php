{{-- resources/views/admin/robux/packages/transactions/show.blade.php --}}
@extends('admin.layout')

@section('title', 'Detail Transaksi - ' . $transaction->transaction_code)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Transaksi {{ $transaction->transaction_code }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.robux.transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <!-- Transaction Info -->
                        <div class="col-md-6">
                            <h5>Informasi Transaksi</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Kode Transaksi:</strong></td>
                                    <td>{{ $transaction->transaction_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Username Roblox:</strong></td>
                                    <td>{{ $transaction->username_roblox }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Robux:</strong></td>
                                    <td>{{ number_format($transaction->robux_amount) }} RBX</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Harga:</strong></td>
                                    <td class="text-success font-weight-bold">{{ $transaction->formatted_price }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @if($transaction->payment_proof_uploaded_at)
                                <tr>
                                    <td><strong>Bukti Diupload:</strong></td>
                                    <td>{{ $transaction->payment_proof_uploaded_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <!-- User Info -->
                        <div class="col-md-6">
                            <h5>Informasi User</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $transaction->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $transaction->user->email }}</td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Status</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status Pembayaran:</strong></td>
                                    <td>
                                        <span class="badge {{ $transaction->status_badge }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status Pengiriman:</strong></td>
                                    <td>
                                        <span class="badge {{ $transaction->delivery_badge }}">
                                            {{ ucfirst($transaction->delivery_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Proof Section -->
                    @if($transaction->payment_proof)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Bukti Pembayaran</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ $transaction->payment_proof_url }}" 
                                                 alt="Bukti Pembayaran" 
                                                 class="img-fluid rounded shadow"
                                                 style="max-height: 400px; cursor: pointer;"
                                                 onclick="openImageModal('{{ $transaction->payment_proof_url }}')">
                                        </div>
                                        <div class="col-md-6">
                                            @if($transaction->payment_note)
                                            <h6>Catatan dari User:</h6>
                                            <div class="alert alert-info">
                                                {{ $transaction->payment_note }}
                                            </div>
                                            @endif
                                            
                                            <p><strong>File:</strong> {{ basename($transaction->payment_proof) }}</p>
                                            <p><strong>Upload:</strong> {{ $transaction->paid_at?->format('d M Y') ?? '-'; }}</p>
                                        
                                            <a href="{{ $transaction->payment_proof_url }}" 
                                               target="_blank" 
                                               class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Full Size
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Actions -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Update Status Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.robux.transactions.update-payment-status', $transaction) }}" 
                                          method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <select name="payment_status" class="form-control" required>
                                                <option value="pending" {{ $transaction->payment_status == 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="waiting_confirmation" {{ $transaction->payment_status == 'waiting_confirmation' ? 'selected' : '' }}>
                                                    Waiting Confirmation
                                                </option>
                                                <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>
                                                    Paid
                                                </option>
                                                <option value="failed" {{ $transaction->payment_status == 'failed' ? 'selected' : '' }}>
                                                    Failed
                                                </option>
                                                <option value="cancelled" {{ $transaction->payment_status == 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="payment_reference" 
                                                   class="form-control" 
                                                   placeholder="Referensi Pembayaran (opsional)"
                                                   value="{{ $transaction->payment_reference }}">
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Update Status Pembayaran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Update Status Pengiriman</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.robux.transactions.update-delivery-status', $transaction) }}" 
                                          method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <select name="delivery_status" class="form-control" required>
                                                <option value="waiting" {{ $transaction->delivery_status == 'waiting' ? 'selected' : '' }}>
                                                    Waiting
                                                </option>
                                                <option value="processing" {{ $transaction->delivery_status == 'processing' ? 'selected' : '' }}>
                                                    Processing
                                                </option>
                                                <option value="completed" {{ $transaction->delivery_status == 'completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                                <option value="failed" {{ $transaction->delivery_status == 'failed' ? 'selected' : '' }}>
                                                    Failed
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-truck"></i> Update Status Pengiriman
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <!-- Admin Notes -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Catatan Admin</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.robux.transactions.update-notes', $transaction) }}" 
                                          method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <textarea name="admin_notes" 
                                                      class="form-control" 
                                                      rows="3" 
                                                      placeholder="Tambahkan catatan admin...">{{ $transaction->admin_notes }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-save"></i> Simpan Catatan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    $('#imageModal').modal('show');
}
</script>
@endsection
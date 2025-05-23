{{-- resources/views/userpov/payment.blade.php --}}
@extends('template')

@section('title', 'Pembayaran - ' . $transaction->transaction_code)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pembayaran Transaksi</h5>
                </div>
                <div class="card-body">
                    <!-- Transaction Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Kode Transaksi:</strong><br>
                            <span class="text-primary">{{ $transaction->transaction_code }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge {{ $transaction->status_badge }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Username Roblox:</strong><br>
                            {{ $transaction->username_roblox }}
                        </div>
                        <div class="col-md-6">
                            <strong>Jumlah Robux:</strong><br>
                            {{ number_format($transaction->robux_amount) }} RBX
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Total Pembayaran:</strong><br>
                            <span class="h5 text-success">{{ $transaction->formatted_price }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Instruksi Pembayaran</h6>
                            <p>Silakan transfer melalui e-wallet:</p>
                            <ul>
                                <li><strong>GoPay:</strong> 0987654321</li>
                                <li><strong>Shopeepay:</strong> 1234567890</li>
                            </ul>
                    </div>

                    <!-- Display Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Upload Payment Proof -->
                    @if($transaction->payment_status == 'pending')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Upload Bukti Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('robux.confirm-payment', $transaction->transaction_code) }}" 
                                      method="POST" enctype="multipart/form-data" id="paymentForm">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label">
                                            <strong>Pilih File Bukti Pembayaran</strong>
                                        </label>
                                        <input type="file" 
                                               class="form-control @error('payment_proof') is-invalid @enderror" 
                                               id="payment_proof" 
                                               name="payment_proof" 
                                               accept="image/jpeg,image/jpg,image/png,image/gif" 
                                               required>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle"></i> 
                                            Pilih foto bukti transfer (JPG, PNG, GIF - maksimal 2MB)
                                        </div>
                                        @error('payment_proof')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="mb-3" id="imagePreview" style="display: none;">
                                        <label class="form-label">Preview:</label>
                                        <div class="border rounded p-2">
                                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                                        </div>
                                    </div>  

                                    <button type="submit" class="btn btn-success" id="submitBtn">
                                        <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($transaction->payment_status == 'waiting_confirmation')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> 
                            Bukti pembayaran sudah diupload dan sedang diverifikasi admin.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Status pembayaran: {{ ucfirst($transaction->payment_status) }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('robux.status', $transaction->transaction_code) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> Cek Status Transaksi
                        </a>
                        <a href="{{ route('robux.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('payment_proof');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('paymentForm');

    // File input change handler
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Tipe file tidak valid! Harap pilih file JPG, PNG, atau GIF.');
                fileInput.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Validate file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                fileInput.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Form submission handler
    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        
        if (!file) {
            e.preventDefault();
            alert('Harap pilih file bukti pembayaran!');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
        
        // Note: Form will submit normally after this
    });
});

function resetForm() {
    document.getElementById('paymentForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-upload"></i> Upload Bukti Pembayaran';
}

// Handle form errors - re-enable button if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Bukti Pembayaran';
        }
    });
@endif
</script>
@endsection
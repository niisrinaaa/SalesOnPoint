@extends('template')

@section('title', 'Beli Robux')

@section('content')

<style>
    .product-card {
        position: relative;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        width: 220px;
        margin: 10px;
        background: white;
        transition: box-shadow 0.3s ease;
    }

    .image-wrapper {
        position: relative;
        width: 100%;
    }

    .image-wrapper img {
        width: 100%;
        display: block;
        border-radius: 5px;
    }

    .price-circle {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background-color: #595a5b;
        color: white;
        font-weight: bold;
        padding: 5px 12px;
        border-radius: 50px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .icon-set {
        position: absolute;
        right: 10px;
        bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .icon-set i {
        color: #333;
        background: #f0f0f0;
        padding: 6px;
        border-radius: 50%;
        cursor: pointer;
    }

    .product-card:hover .price-circle,
    .product-card:hover .icon-set {
        opacity: 1;
    }

    .rating {
        color: gold;
        font-size: 16px;
    }

    .rbx-option.selected {
        background-color: #007bff;
        color: white;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-4">Tipe Pembelian Robux</h4>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('robux.purchase') }}" method="POST" id="purchaseForm">
                        @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Username Roblox <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="username_roblox" 
                                           value="{{ old('username_roblox') }}" 
                                           placeholder="Masukkan username Roblox Anda" required>
                                </div>
                                @error('username_roblox')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <label class="form-label">Pilih Jumlah Robux</label>
                            <div class="mb-4">
                                <div class="d-flex flex-wrap">
                                    @forelse($packages as $package)
                                        <div class="rbx-option">
                                            <button type="button" class="btn btn-outline-secondary m-2 package-btn" 
                                                    data-package-id="{{ $package->id }}"
                                                    data-amount="{{ $package->amount }}"
                                                    data-price="{{ $package->price }}"
                                                    @if($package->stock <= 0) disabled @endif>
                                                {{ number_format($package->amount) }} Robux
                                                @if($package->stock <= 0)
                                                    <br><small class="text-danger">Stok Habis</small>
                                                @else
                                                    <br><small class="text-muted">Stok: {{ $package->stock }}</small>
                                                @endif
                                            </button>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">
                                            Tidak ada paket yang tersedia saat ini.
                                        </div>
                                    @endforelse
                                </div>
                                <input type="hidden" name="package_id" id="selectedPackageId">
                            </div>

                            <label class="form-label">Atau Kustom Jumlah Robux</label>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" name="custom_amount" 
                                               id="customAmount" min="100" step="100" 
                                               placeholder="Minimal 100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="customPrice" 
                                               placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   value="e_wallet" id="e_wallet" required>
                                            <label class="form-check-label" for="e_wallet">
                                                E-Wallet
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="mb-3 fw-bold">Rincian Biaya</h5>
                
                <div class="mb-3" id="orderSummary" style="display: none;">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jumlah Robux:</span>
                        <span id="summaryAmount">0 Robux</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Harga per Robux:</span>
                        <span>Rp 109</span>
                    </div>
                    <hr>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Total Biaya</span>
                    <div>  
                        <span class="text-success fw-semibold" id="totalPrice">Rp 0</span>
                    </div>
                </div>

                <button type="submit" form="purchaseForm" class="btn btn-primary w-100 fw-semibold" 
                        id="purchaseBtn" disabled>
                    Lanjutkan Pembelian
                </button>
                
                <small class="text-muted mt-2">
                    <i class="bi bi-shield-check"></i> Transaksi aman dan terpercaya
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageBtns = document.querySelectorAll('.package-btn');
    const customAmountInput = document.getElementById('customAmount');
    const customPriceInput = document.getElementById('customPrice');
    const selectedPackageInput = document.getElementById('selectedPackageId');
    const totalPriceElement = document.getElementById('totalPrice');
    const summaryAmountElement = document.getElementById('summaryAmount');
    const orderSummary = document.getElementById('orderSummary');
    const purchaseBtn = document.getElementById('purchaseBtn');

    let selectedAmount = 0;
    let selectedPrice = 0;

    // Handle paket selection
    packageBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove previous selection
            packageBtns.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');

            // Clear custom amount
            customAmountInput.value = '';
            customPriceInput.value = '';

            // Set values
            const packageId = this.dataset.packageId;
            const amount = parseInt(this.dataset.amount);
            const price = parseInt(this.dataset.price);

            selectedPackageInput.value = packageId;
            selectedAmount = amount;
            selectedPrice = price;

            updateSummary();
        });
    });

    // Handle custom amount
    customAmountInput.addEventListener('input', function() {
        const amount = parseInt(this.value) || 0;
        
        if (amount >= 100) {
            // Clear package selection
            packageBtns.forEach(btn => btn.classList.remove('selected'));
            selectedPackageInput.value = '';

            // Calculate price
            const price = amount * 109;
            customPriceInput.value = number_format(price);

            selectedAmount = amount;
            selectedPrice = price;

            updateSummary();
        } else {
            customPriceInput.value = '';
            selectedAmount = 0;
            selectedPrice = 0;
            updateSummary();
        }
    });

    function updateSummary() {
        if (selectedAmount > 0) {
            summaryAmountElement.textContent = number_format(selectedAmount) + ' Robux';
            totalPriceElement.textContent = 'Rp ' + number_format(selectedPrice);
            orderSummary.style.display = 'block';
            purchaseBtn.disabled = false;
        } else {
            orderSummary.style.display = 'none';
            totalPriceElement.textContent = 'Rp 0';
            purchaseBtn.disabled = true;
        }
    }

    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Form validation
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const username = document.querySelector('input[name="username_roblox"]').value;
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!username.trim()) {
            e.preventDefault();
            alert('Username Roblox harus diisi!');
            return;
        }

        if (!paymentMethod) {
            e.preventDefault();
            alert('Pilih metode pembayaran!');
            return;
        }

        if (selectedAmount === 0) {
            e.preventDefault();
            alert('Pilih paket atau masukkan jumlah custom!');
            return;
        }
    });
});
</script>

@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Robux - Cepat & Murah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .robux-card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .robux-card:hover, .robux-card.selected {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .price-tag {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px;
            padding: 5px 15px;
            position: absolute;
            top: -10px;
            right: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">🎮 Top Up Robux</h1>
                    <p class="lead text-muted">Beli Robux dengan cepat, aman, dan harga terjangkau!</p>
                </div>

                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form id="topupForm">
                            <!-- Username Input -->
                            <div class="mb-4">
                                <label for="username" class="form-label fw-bold">Username Roblox</label>
                                <div class="input-group">
                                    <span class="input-group-text">👤</span>
                                    <input type="text" class="form-control form-control-lg" 
                                           id="username" name="username_roblox" 
                                           placeholder="Masukkan username Roblox Anda" required>
                                </div>
                                <div class="form-text">Pastikan username Roblox Anda benar!</div>
                            </div>

                            <!-- Package Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih Paket Robux</label>
                                <div class="row g-3" id="packageContainer">
                                    <!-- Packages akan dimuat via JavaScript -->
                                </div>
                            </div>

                            <!-- Selected Package Info -->
                            <div id="selectedPackageInfo" class="alert alert-info d-none">
                                <strong>Paket Dipilih:</strong> <span id="selectedAmount"></span> Robux<br>
                                <strong>Total Harga:</strong> <span id="selectedPrice"></span>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                    💳 Lanjutkan Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- How it Works -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">📋 Cara Pembelian</h5>
                        <ol class="mb-0">
                            <li>Masukkan username Roblox Anda</li>
                            <li>Pilih paket Robux yang diinginkan</li>
                            <li>Klik "Lanjutkan Pembayaran"</li>
                            <li>Selesaikan pembayaran</li>
                            <li>Robux akan dikirim ke akun Anda dalam 1-5 menit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✅ Pesanan Berhasil!</h5>
                </div>
                <div class="modal-body text-center">
                    <h4>Kode Transaksi:</h4>
                    <h3 class="text-primary" id="transactionCode"></h3>
                    <p class="mt-3">Silakan lakukan pembayaran sesuai total yang tertera. Robux akan dikirim setelah pembayaran dikonfirmasi.</p>
                    <div class="alert alert-warning">
                        <strong>Simpan kode transaksi ini</strong> untuk melacak status pesanan Anda!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.reload()">Buat Pesanan Baru</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedPackage = null;
        let packages = [];

        // Load packages saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPackages();
        });

        async function loadPackages() {
            try {
                const response = await fetch('/api/robux/packages');
                const data = await response.json();
                
                if (data.success) {
                    packages = data.data;
                    renderPackages();
                }
            } catch (error) {
                console.error('Error loading packages:', error);
            }
        }

        function renderPackages() {
            const container = document.getElementById('packageContainer');
            container.innerHTML = '';

            packages.forEach(package => {
                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `
                    <div class="card robux-card h-100 position-relative" onclick="selectPackage(${package.id})">
                        <div class="price-tag">${package.formatted_price}</div>
                        <div class="card-body text-center">
                            <h4 class="text-primary">${package.amount.toLocaleString()} RBX</h4>
                            <p class="text-muted mb-0">${package.description || ''}</p>
                            <small class="text-success">Stok: ${package.stock}</small>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        function selectPackage(packageId) {
            // Reset previous selection
            document.querySelectorAll('.robux-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Select current package
            event.currentTarget.classList.add('selected');
            selectedPackage = packages.find(p => p.id === packageId);

            // Update UI
            document.getElementById('selectedAmount').textContent = selectedPackage.amount.toLocaleString();
            document.getElementById('selectedPrice').textContent = selectedPackage.formatted_price;
            document.getElementById('selectedPackageInfo').classList.remove('d-none');
            document.getElementById('submitBtn').disabled = false;
        }

        // Handle form submission
        document.getElementById('topupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!selectedPackage) {
                alert('Silakan pilih paket Robux terlebih dahulu!');
                return;
            }

            const username = document.getElementById('username').value.trim();
            if (!username) {
                alert('Silakan masukkan username Roblox Anda!');
                return;
            }

            // Disable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Memproses...';

            try {
                const response = await fetch('/api/robux/create-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        username_roblox: username,
                        package_id: selectedPackage.id
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Show success modal
                    document.getElementById('transactionCode').textContent = data.data.transaction_code;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan, silakan coba lagi!');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '💳 Lanjutkan Pembayaran';
            }
        });
    </script>
</body>
</html>
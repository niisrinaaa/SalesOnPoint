@extends('template')

@section('title', 'Keranjang Belanja')

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
    </style>

<div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="container">
                                <div class="card shadow-sm">
                                <div class="card-body">
                                    <h4 class="mb-4">Tipe Pembelian Robux</h4>

                                    <div class="mb-3">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-secondary">1 User</button>
                                        {{-- <button class="btn btn-outline-secondary">Banyak User</button> --}}
                                    </div>
                                    </div>

                                    <div class="mb-3">
                                    <label class="form-label">Username Roblox</label>
                                    <div class="input-group">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="form-control" value="">
                                    </div>
                                    </div>

                                    <label class="form-label">Pilih Jumlah Robux</label>
                                    <div class="rb">
                                        <div class="d-flex mb-4">
                                        <div class="rbx-option">
                                            <button class="btn btn-outline-secondary m-2">100 RBX</button>
                                        </div>
                                        <div class="rbx-option">
                                            
                                            <button class="btn btn-outline-secondary m-2" >500 RBX</button>
                                        </div>
                                        <div class="rbx-option">
                                            
                                            <button class="btn btn-outline-secondary m-2" >1000 RBX</button>
                                        </div>
                                        <div class="rbx-option">
                                            
                                            <button class="btn btn-outline-secondary m-2" >5000 RBX</button>
                                        </div>
                                    </div>
                                    </div>

                                    <label class="form-label">Atau Kustom Jumlah Robux</label>
                                    <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" value="100">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" value="10.900" readonly>
                                        </div>
                                    </div>
                                    </div>

                                    <a href="#" class="text-success text-decoration-none">📘 Cara Membeli</a>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card p-3">
                                <h5 class="mb-3 fw-bold">Rincian Biaya</h5>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold">Total Biaya</span>
                                    <div>  
                                    <span class="text-success fw-semibold">Rp107.000</span>
                                    </div>
                                </div>

                                <button class="btn btn-success w-100 fw-semibold">Lanjutkan Pembelian</button>
                            </div>
                        </div>

                    </div>
                </div>
@endsection
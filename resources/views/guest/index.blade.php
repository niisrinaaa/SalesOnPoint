@extends('template')

@section('title', 'Belanja Sekarang')

@section('content')

<style>
    .card-custom {
        border-radius: 20px;
    }

    .rbx-btn {
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        margin: 15px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #343635;
    }

    .rbx-btn:hover {
        background-color: #b5bab8;
        border-color: #818282;
        text-decoration: none;
        color: #343635;
    }

    .rbx-btn.active {
        background-color: #c2c5c4;
        border-color: #343635;
    }
</style>


    <div class="col-md-12">
                    <div class=" justify-content-center">
                        <div class="card m-3">
                            <img src="{{asset ('img/bannerbajakrobux.png')}}" alt="">
                        </div>
                        <div class="card card-custom shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Top Up Robux</h4>
                                <a href="#" class="text-success text-decoration-none">Lihat Ranking &raquo;</a>
                            </div>

                            <div class="row">
                                <!-- Stok Robux -->
                                <div class="col-md-6 mb-3">
                                    <div class="border p-3 h-100 d-flex flex-column justify-content-between">
                                        
                                        <div class="border p-3 d-flex justify-content-between mt-2">
                                            <div>
                                                <strong>Stok Robux</strong>
                                            </div>
                                            <div>
                                                <img src="https://img.icons8.com/color/48/robux.png" width="24" alt="robux-icon">
                                                <span class="ms-2 fs-5">99,234k</span>
                                            </div>
                                        </div>

                                        <a href="buying" class="btn btn-success w-100">Beli Robux Sekarang</a>
                                    </div>
                                </div>

                                <!-- Pilih Cepat -->
                                <div class="col-md-6 mb-3 border p-3">
                                    <strong class="d-block mb-2">Pilih Cepat</strong>
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="buying" class="rbx-btn">
                                            <img src="https://img.icons8.com/color/48/robux.png" width="24" alt="robux-icon"><br>
                                            100 RBX 
                                        </a>
                                        <a href="buying" class="rbx-btn">
                                            <img src="https://img.icons8.com/color/48/robux.png" width="24" alt="robux-icon"><br>
                                            500 RBX
                                        </a>
                                        <a href="buying" class="rbx-btn">
                                            <img src="https://img.icons8.com/color/48/robux.png" width="24" alt="robux-icon"><br>
                                            1000 RBX
                                        </a>
                                        <a href="buying" class="rbx-btn">
                                            <img src="https://img.icons8.com/color/48/robux.png" width="24" alt="robux-icon"><br>
                                            5000 RBX
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
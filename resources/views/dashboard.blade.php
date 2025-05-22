{{-- @extends('layout')
@section('title', 'Home')
@section('content-title', 'Dashboard')
@section('content')

<style>
    .bjs {
        
    }

    .prais {

        margin-top: 30px;
        font-size: xx-large;
    }

    img {
        width: 100px;
        height: 100px;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <div class="container border p-4 d-flex justify-content-between">
                <div class="">
                    <img src="https://img.icons8.com/color/48/robux.png" alt="Bajak Robux">
                </div>
                <div class="prais">
                    <span class="text-success">99,234R</span>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Stock</h4> </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Masukkan Jumlah Robux</label>
                            <input type="number" class="form-control @error('') is-invalid  @enderror" name="name" value="" placeholder="">
                            @error('')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>
                    
                            <button type="submit" class="btn btn-sm btn-primary me-3">SAVE</button>
                            <button type="reset" class="btn btn-sm btn-warning">RESET</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

{{-- resources/views/create.blade.php --}}
@extends('layout')

@section('title', 'Create Data')
@section('content-title', 'Create ' . ucfirst($type))
@section('content')
<div class="col-md-8">
    <h1>Create {{ ucfirst($type) }}</h1>

    @if($type == 'Category')
    <form action="{{ route('category.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label class="font-weight-bold">Masukkan Category</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan Judul Product">
        </div>

        <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
        <button type="reset" class="btn btn-md btn-warning">RESET</button>
    </form>

    @elseif($type == 'Item')
    <form action="{{ route('item.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label class="font-weight-bold">Nama Item</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan Judul Product">
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">PRICE</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="Masukkan Harga Product">
        </div>
        <div class="mb-3">
            <div class="form-group mb-3">
                <label class="font-weight-bold">STOCK</label>
                <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}" placeholder="Masukkan Stock Product">
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="categories_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
        <button type="reset" class="btn btn-md btn-warning">RESET</button>
    </form>
    @elseif($type == 'transaction')
    <form action="{{ route('transaction.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">User</label>
            <select name="user_id" class="form-control">
                @foreach($user as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">Date</label>
            <input type="date" class="form-control @error('datetime') is-invalid @enderror" name="datetime" value="{{ old('datetime') }}" placeholder="Masukkan Tanggal">
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">total</label>
            <input type="number" class="form-control @error('total') is-invalid @enderror" name="total" value="{{ old('total') }}" placeholder="Masukkan Total ">
        </div>
        <div class="mb-3">
            <div class="form-group mb-3">
                <label class="font-weight-bold">pay total</label>
                <input type="number" class="form-control @error('pay_total') is-invalid @enderror" name="pay_total" value="{{ old('pay_total') }}" placeholder="Masukkan Pay_total ">
        </div>
        <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
        <button type="reset" class="btn btn-md btn-warning">RESET</button>
    </form>
    {{-- @elseif($type == 'transactionDetail')
    <form action="{{ route('detail.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">ID Transaction</label>
            <select name="transaction_id" class="form-control">
                @foreach($TransactionDetails as $TransactionDetail)
                    <option value="{{ $TransactionDetail->id }}">{{ $TransactionDetail->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">ID Items</label>
            <select name="transaction_id" class="form-control">
                @foreach($TransactionDetails as $TransactionDetail)
                    <option value="{{ $TransactionDetail->id }}">{{ $TransactionDetail->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">total</label>
            <input type="number" class="form-control @error('total') is-invalid @enderror" name="total" value="{{ old('total') }}" placeholder="Masukkan Total ">
        </div>
        <div class="mb-3">
            <div class="form-group mb-3">
                <label class="font-weight-bold">pay total</label>
                <input type="number" class="form-control @error('pay_total') is-invalid @enderror" name="pay_total" value="{{ old('pay_total') }}" placeholder="Masukkan Pay_total ">
        </div>
        <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
        <button type="reset" class="btn btn-md btn-warning">RESET</button>
    </form> --}}
    @endif()
</div>
@endsection

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

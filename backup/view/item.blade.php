
@extends('admin.layout')
@section('title', 'Home')
@section('content-title', 'Master Item')
@section('content')
<div class="col-md-12">
  <div class="row">
<div class="col-md-8">
  <div class="card">
    <div class="card-header">Data Items</div>
    <div class="card-body">
    {{-- <a class="btn btn-success" href="{{ route('item.create') }}">ADD Data</a> //jika ingin menggunakan ke file create --}} 
    <table class="table table-striped">
      <thead>
        <tr>
          <td>id</td>
          <td>Name</td>
          <td>Kategori</td>
          <td>stock</td>
          <td>kategori</td>
          <td>Action</td>
          </tr>
        </thead>
          @forelse ($Items as $Item)
          <tr>
            <td>{{ $loop -> iteration }}</td>
            <td>{{$Item -> name}}</td>
            <td>{{$Item -> price}}</td>
            <td>{{$Item -> stock}}</td>
            <td>{{ $Item -> Category -> name}}</td>
            <td>
              <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('admin.item.destroy', $Item->id) }}" method="POST">
                <a href="{{ route('admin.item.edit', $Item->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
            </form>
            </td>
          </tr>  
          @empty
              
          @endforelse
    </table>
  </div>
</div>
</div>



  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Tambah Item</h4> </div>
        <div class="card-body">
    <form action="{{ route('admin.item.store') }}" method="POST">
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
              @foreach($Categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
          </select>
      </div>
      <button type="submit" class="btn btn-md btn-primary me-3">SAVE</button>
      <button type="reset" class="btn btn-md btn-warning">RESET</button>
  </form>
  </div>
</div>
</div>
</div>
</div>
  @endsection
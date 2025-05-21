
@extends('layout')
@section('title', 'Home')
@section('content-title', 'Category')
@section('content')

@session('success')
  <div class="alert alert-success">{{session('success')}}</div>
@endsession


<div class="col-md-12">
  <div class="row">
<div class="col-md-8">
  <div class="card">
    <div class="card-header">Data Category</div>
    <div class="card-body">
    {{-- <a class="btn btn-success" href="{{ route('category.create') }}">ADD Data</a> --}}
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <td>id</td>
          <td>Name</td>
          <td>Action</td>
          </tr>
        </thead>
          @forelse ($Categories as $Category)
          
          <tr>
            <td>{{ $loop -> iteration }}</td>
            <td>{{$Category -> name}}</td>
            <td>
              <a href="{{ route('category.edit', $Category) }}" class="btn btn-sm btn-primary">EDIT</a>
              <form class="d-inline" onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('category.destroy', $Category->id) }}" method="POST">
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
        <h4 class="card-title">Tambah Category</h4> </div>
        <div class="card-body">
        <form action="{{ route('category.store') }}" method="POST">
          @csrf
          @method('POST')
          <div class="form-group mb-3">
              <label class="font-weight-bold">Masukkan Nama Category</label>
              <input type="text" class="form-control @error('name') is-invalid  @enderror" name="name" value="{{ old('name') }}" placeholder="Nama Kategori">
              @error('name')
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
  @endsection
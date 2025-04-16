
@extends('layout')
@section('title', 'Home')
@section('content-title', 'Category')
@section('content')
<div class="col-md-8">
    <a class="btn btn-success" href="">ADD Data</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <td>id</td>
          <td>Name</td>
          </tr>
          @forelse ($Categories as $Category)
          <tr>
            <td>{{ $loop -> iteration }}</td>
            <td>{{$Category -> name}}</td>
            <td>
              <a class="btn btn-sm btn-warning" href="">Edit</a>
              <a class="btn btn-sm btn-danger" href="">Hapus</a>
            </td>
          </tr>  
          @empty
              
          @endforelse
          </thead>
    </table>
  </div>
  <div class="col-md-4">
  </div>
  @endsection
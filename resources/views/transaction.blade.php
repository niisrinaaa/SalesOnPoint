
@extends('layout')
@section('title', 'Home')
@section('content-title', 'Transaction')
@section('content')
<div class="col-md-12">
    <a class="btn btn-success" href="{{ route('transaction.create') }}">ADD Data</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <td>id</td>
          <td>User Id</td>
          <td>User Name</td>
          <td>Email</td>
            <td>Date</td>
            <td>Total</td>
            <td>Pay Total</td>
            <td>Action</td>
          </tr>
          @forelse ($Transactions as $Transaction)
          <tr>
            <td>{{ $Transaction -> id }}</td>
            <td>{{$Transaction -> user_id}}</td>
            <td>{{$Transaction -> User -> name}}</td>
            <td>{{$Transaction -> User -> email}}</td>
            <td>{{$Transaction -> datetime}}</td>
            <td>{{$Transaction -> total}}</td>
            <td>{{$Transaction -> pay_total}}</td>
            <td>
              <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('transaction.destroy', $Transaction->id) }}" method="POST">
                <a href="{{ route('transaction.show', $Transaction->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                <a href="{{ route('transaction.edit', $Transaction->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
            </form>
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
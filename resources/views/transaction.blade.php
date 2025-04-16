
@extends('layout')
@section('title', 'Home')
@section('content-title', 'Transaction')
@section('content')
<div class="col-md-8">
    <a class="btn btn-success" href="">ADD Data</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <td>id</td>
          <td>User Id</td>
            <td>Date</td>
            <td>Total</td>
            <td>Pay Total</td>
          </tr>
          @forelse ($Transactions as $Transaction)
          <tr>
            <td>{{ $Transaction -> id }}</td>
            <td>{{$Transaction -> user_id}}</td>
            <td>{{$Transaction -> datetime}}</td>
            <td>{{$Transaction -> total}}</td>
            <td>{{$Transaction -> pay_total}}</td>
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
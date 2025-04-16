
@extends('layout')
@section('title', 'Home')
@section('content-title', 'history')
@section('content')
<div class="col-md-8">
    <a class="btn btn-success" href="">ADD Data</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <td>id</td>
          <td>Transaction Id</td>
            <td>Item id</td>
            <td>qty</td>
            <td>subtotal</td>
          </tr>
          @forelse ($TransactionDetails as $TransactionDetail)
          <tr>
            <td>{{ $loop -> iteration }}</td>
            <td>{{$TransactionDetail -> transaction_id}}</td>
            <td>{{$TransactionDetail -> item_id}}</td>
            <td>{{$TransactionDetail -> qty}}</td>
            <td>{{$TransactionDetail -> subtotal}}</td>
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
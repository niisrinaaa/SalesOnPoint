
@extends('admin.layout')
@section('title', 'Home')
@section('content-title', 'history')
@section('content')
<div class="col-md-8">
    <a class="btn btn-success" href="{{ route('detail.create') }}">ADD Data</a>
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
              <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('detail.destroy', $TransactionDetail->id) }}" method="POST">
                <a href="{{ route('detail.show', $TransactionDetail->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                <a href="{{ route('detail.edit', $TransactionDetail->id) }}" class="btn btn-sm btn-primary">EDIT</a>
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
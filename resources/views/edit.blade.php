@extends('layout')
@section('title', 'Edit')
@section('content-title', 'Edit')
@section('content')
<div class="container">
    <h1>Edit {{ ucfirst($type) }}</h1>

    @if($type == 'Category')
        <form action="{{ route('Category.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name" value="{{ $data->name }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>

    @elseif($type == 'item')
        <form action="{{ route('item.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" class="form-control" name="name" value="{{ $data->name }}">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" name="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Item</button>
        </form>
    @endif
</div>
@endsection
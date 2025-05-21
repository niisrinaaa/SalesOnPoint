@extends('layout')
@section('title', 'Edit')
@section('content-title',)
@section('content')
<div class="container">
    <h1>{{ ucfirst($type) }}</h1>

    @if($type == 'Category')
        <form action="{{ route('category.update', $category->id ) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name" value="{{ $category->name }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>

    @elseif($type == 'Item')
        <form action="{{ route('item.update', $Item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" class="form-control" name="name" value="{{ $Item->name }}">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Harga</label>
                <input type="text" class="form-control" name="price" value="{{ $Item->price }}">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">stok</label>
                <input type="text" class="form-control" name="stock" value="{{ $Item->stock }}">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" name="categories_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $Item->categories_id == $category->id ? 'selected' : '' }}>
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
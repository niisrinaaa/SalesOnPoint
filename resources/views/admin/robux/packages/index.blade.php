@extends('admin.layout')
@section('title', 'Robux Packages')
@section('content-title', 'Kelola Paket Robux')
@section('content')

@session('success')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endsession

@session('error')
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endsession

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Paket Robux</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jumlah Robux</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ number_format($package->amount) }} Robux</strong>
                                    @if($package->description)
                                    <br><small class="text-muted">{{ $package->description }}</small>
                                    @endif
                                </td>
                                <td>{{ $package->formatted_price }}</td>
                                <td>
                                    <span class="badge {{ $package->stock < 10 ? 'badge-danger' : 'badge-success' }}">
                                        {{ $package->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $package->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>           
                                    <!-- Update Stock Button -->
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            data-toggle="modal" data-target="#updateStockModal{{ $package->id }}">
                                        Update Stok
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    @if($package->transactions->count() == 0)
                                    <form class="d-inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')" 
                                          action="{{ route('admin.robux.packages.destroy', $package) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Update Stock -->
                            <div class="modal fade" id="updateStockModal{{ $package->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Stok - {{ $package->amount }} Robux</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.robux.packages.update-stock', $package) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Stok Saat Ini</label>
                                                    <input type="text" class="form-control" value="{{ $package->stock }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Stok Baru</label>
                                                    <input type="number" name="new_stock" class="form-control" 
                                                           value="{{ $package->stock }}" min="0" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Catatan (Opsional)</label>
                                                    <textarea name="notes" class="form-control" rows="3" 
                                                              placeholder="Alasan update stok..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Stok</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada paket Robux</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Tambah Paket -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Paket Robux Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.robux.packages.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Jumlah Robux</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount') }}" placeholder="Contoh: 100" min="1" required>
                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price') }}" placeholder="Contoh: 10900" min="0" step="0.01" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', 0) }}" placeholder="Contoh: 100" min="0" required>
                        @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" 
                               value="{{ old('description') }}" placeholder="Contoh: Paket untuk pemula">
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_active" class="custom-control-input" 
                                 id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Aktifkan paket</label>
                        </div>
                    </div> --}}

                    <button type="submit" class="btn btn-primary btn-block">Tambah Paket</button>
                    <button type="reset" class="btn btn-secondary btn-block">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

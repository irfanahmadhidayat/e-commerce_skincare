@extends('adminlte::page')

@section('title', $produk ? 'Edit Produk' : 'Tambah Produk')
@section('content_header') <h1>{{ $produk ? 'Edit Produk' : 'Tambah Produk' }}</h1> @stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ $produk ? route('admin.produk.update', $produk) : route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($produk) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label>Nama *</label><input name="nama" value="{{ old('nama', $produk?->nama) }}" class="form-control" required></div>
                        <div class="mb-3"><label>Slug *</label><input name="slug" value="{{ old('slug', $produk?->slug) }}" class="form-control" required></div>
                        <div class="mb-3"><label>Kategori *</label>
                            <select name="kategori_id" class="form-select" required>
                                @foreach($kategoriList as $k)<option value="{{ $k->id }}" @selected(old('kategori_id', $produk?->kategori_id) == $k->id)>{{ $k->nama }}</option>@endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label>Harga *</label><input type="number" step="0.01" name="harga" value="{{ old('harga', $produk?->harga) }}" class="form-control" required></div>
                        <div class="mb-3"><label>Stok *</label><input type="number" name="stok" value="{{ old('stok', $produk?->stok) }}" class="form-control" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3"><label>Foto</label><input type="file" name="foto" class="form-control"></div>
                        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $produk?->deskripsi) }}</textarea></div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="aktif" value="1" @checked((bool) ($produk?->aktif ?? true))>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
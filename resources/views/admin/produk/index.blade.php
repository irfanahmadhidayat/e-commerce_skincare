@extends('adminlte::page')

@section('title', 'Kelola Produk')
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Kelola Produk</h1>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>
@stop

@section('content')
    @if (session('sukses')) <div class="alert alert-success">{{ session('sukses') }}</div> @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($produk as $p)
                    <tr>
                        <td><img src="{{ $p->foto ? asset('storage/'.$p->foto) : 'https://placehold.co/40x40?text=Produk' }}" class="rounded" width="40" height="40"></td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->kategori?->nama }}</td>
                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>{{ $p->stok }}</td>
                        <td>@if($p->aktif) <span class="badge text-bg-success">Aktif</span> @else <span class="badge text-bg-secondary">Nonaktif</span> @endif</td>
                        <td>
                            <a href="{{ route('admin.produk.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.produk.destroy', $p) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
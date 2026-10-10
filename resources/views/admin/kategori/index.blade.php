@extends('adminlte::page')

@section('title', 'Kelola Kategori')
@section('content_header') <h1>Kelola Kategori</h1> @stop

@section('content')
    @if(session('sukses')) <div class="alert alert-success">{{ session('sukses') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Tambah Kategori</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-3"><label>Nama *</label><input name="nama" class="form-control" required></div>
                        <div class="mb-3"><label>Slug *</label><input name="slug" class="form-control" required></div>
                        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" rows="2" class="form-control"></textarea></div>
                        <button class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Nama</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <td>{{ $k->nama }}</td>
                                <td>{{ $k->produk_count }}</td>
                                <td>@if($k->aktif) <span class="badge text-bg-success">Aktif</span> @else <span class="badge text-bg-secondary">Nonaktif</span> @endif</td>
                                <td>
                                    <form action="{{ route('admin.kategori.toggle', $k) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $k->aktif ? 'btn-secondary' : 'btn-success' }}">{{ $k->aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
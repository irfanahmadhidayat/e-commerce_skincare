@extends('adminlte::page')

@section('title', 'Dashboard Admin')
@section('content_header') <h1>Dashboard</h1> @stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-6"><div class="info-box"><span class="info-box-icon bg-info"><i class="bi bi-box-seam"></i></span><div class="info-box-content"><span class="info-box-text">Produk</span><span class="info-box-number">{{ $jumlahProduk }}</span></div></div></div>
        <div class="col-md-3 col-6"><div class="info-box"><span class="info-box-icon bg-success"><i class="bi bi-receipt"></i></span><div class="info-box-content"><span class="info-box-text">Pesanan</span><span class="info-box-number">{{ $jumlahPesanan }}</span></div></div></div>
        <div class="col-md-3 col-6"><div class="info-box"><span class="info-box-icon bg-warning"><i class="bi bi-tags"></i></span><div class="info-box-content"><span class="info-box-text">Kategori</span><span class="info-box-number">{{ $jumlahKategori }}</span></div></div></div>
        <div class="col-md-3 col-6"><div class="info-box"><span class="info-box-icon bg-danger"><i class="bi bi-people"></i></span><div class="info-box-content"><span class="info-box-text">Pengguna</span><span class="info-box-number">{{ $jumlahPengguna }}</span></div></div></div>
    </div>

    <div class="card mt-3">
        <div class="card-header"><h3 class="card-title">Pesanan Menunggu Pembayaran</h3></div>
        <div class="card-body"><strong>{{ $pesananMenunggu }}</strong> pesanan menunggu verifikasi.</div>
    </div>
@stop
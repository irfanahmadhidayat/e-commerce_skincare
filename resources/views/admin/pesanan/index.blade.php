@extends('adminlte::page')

@section('title', 'Pesanan & Pembayaran')
@section('content_header') <h1>Pesanan & Pembayaran</h1> @stop

@section('content')
    @if(session('sukses')) <div class="alert alert-success">{{ session('sukses') }}</div> @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Nomor</th><th>Customer</th><th>Tanggal</th><th>Total</th><th>Status Pesanan</th><th>Pembayaran</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($pesanan as $p)
                    <tr>
                        <td>{{ $p->nomor }}</td>
                        <td>{{ $p->customer?->name }}</td>
                        <td>{{ $p->tanggal->format('d M Y') }}</td>
                        <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td><span class="badge text-bg-{{ $p->status === 'dibayar' ? 'success' : ($p->status === 'menunggu pembayaran' ? 'warning' : 'danger') }}">{{ $p->status }}</span></td>
                        <td>
                            @if($p->pembayaran)
                                {{ $p->pembayaran->metode }} — {{ $p->pembayaran->status }}
                                @if($p->pembayaran->referensi)<br><small>Ref: {{ $p->pembayaran->referensi }}</small>@endif
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td>
                            @if($p->status === 'menunggu pembayaran' && $p->pembayaran && $p->pembayaran->status === 'menunggu')
                                <form action="{{ route('admin.pesanan.verifikasiPembayaran', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Verifikasi</button>
                                </form>
                                <form action="{{ route('admin.pesanan.tolak', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@extends('adminlte::page')

@section('title', 'Data Toko')
@section('content_header') <h1>Data Toko</h1> @stop

@section('content')
    @if(session('sukses')) <div class="alert alert-success">{{ session('sukses') }}</div> @endif

    @php
        $rekening = collect($toko->rekening ?? [])->values();
        $rekeningRows = [];
        for ($i = 0; $i < 3; $i++) {
            $rekeningRows[] = [
                'bank' => $rekening[$i]['bank'] ?? '',
                'nomor' => $rekening[$i]['nomor'] ?? '',
                'atas_nama' => $rekening[$i]['atas_nama'] ?? '',
            ];
        }
    @endphp

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.toko.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3"><label>Nama Toko *</label><input name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" class="form-control" required></div>
                <div class="mb-3"><label>Kontak</label><input name="kontak" value="{{ old('kontak', $toko->kontak) }}" class="form-control"></div>
                <div class="mb-3"><label>Alamat</label><textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $toko->alamat) }}</textarea></div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" rows="2" class="form-control">{{ old('deskripsi', $toko->deskripsi) }}</textarea></div>
                <div class="mb-3">
                    <label>Rekening Bank</label>
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th style="width: 25%">Bank</th>
                                <th style="width: 35%">Nomor Rekening</th>
                                <th style="width: 40%">Atas Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 3; $i++)
                                <tr>
                                    <td>
                                        <input name="rekening[{{ $i }}][bank]" value="{{ old("rekening.$i.bank", $rekeningRows[$i]['bank']) }}" class="form-control" placeholder="BCA">
                                    </td>
                                    <td>
                                        <input name="rekening[{{ $i }}][nomor]" value="{{ old("rekening.$i.nomor", $rekeningRows[$i]['nomor']) }}" class="form-control" placeholder="1234567890">
                                    </td>
                                    <td>
                                        <input name="rekening[{{ $i }}][atas_nama]" value="{{ old("rekening.$i.atas_nama", $rekeningRows[$i]['atas_nama']) }}" class="form-control" placeholder="a.n. Toko Skincare">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <small class="text-muted">Isi baris yang diperlukan saja; baris kosong akan diabaikan.</small>
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@stop
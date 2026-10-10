<?php

use App\Models\Kategori;
use App\Models\Produk;
use App\Services\Keranjang;
use Illuminate\Support\Str;

function keranjangBuatProduk(int $stok = 10, float $harga = 100000): Produk
{
    $kategori = new Kategori;
    $kategori->nama = 'Kategori '.Str::random(4);
    $kategori->slug = 'kategori-'.Str::random(6);
    $kategori->deskripsi = null;
    $kategori->aktif = true;
    $kategori->save();

    $produk = new Produk;
    $produk->kategori_id = $kategori->id;
    $produk->nama = 'Produk '.Str::random(4);
    $produk->slug = 'produk-'.Str::random(6);
    $produk->deskripsi = null;
    $produk->harga = $harga;
    $produk->stok = $stok;
    $produk->foto = null;
    $produk->aktif = true;
    $produk->save();

    return $produk;
}

beforeEach(function () {
    session()->flush();

    $this->keranjang = app(Keranjang::class);
});

test('menambahkan produk ke keranjang', function () {
    $produk = keranjangBuatProduk();

    $this->keranjang->tambah($produk->id);

    expect($this->keranjang->count())->toBe(1)
        ->and($this->keranjang->items()[0]['produk']->id)->toBe($produk->id);
});

test('tidak menambahkan produk dengan stok habis', function () {
    $produk = keranjangBuatProduk(stok: 0);

    $this->keranjang->tambah($produk->id);

    expect($this->keranjang->count())->toBe(0);
});

test('jumlah dibatasi tidak melebihi stok', function () {
    $produk = keranjangBuatProduk(stok: 2);

    $this->keranjang->tambah($produk->id, 5);

    expect($this->keranjang->count())->toBe(2);
});

test('mengubah jumlah menjadi nol menghapus item', function () {
    $produk = keranjangBuatProduk();

    $this->keranjang->tambah($produk->id);
    $this->keranjang->ubah($produk->id, 0);

    expect($this->keranjang->count())->toBe(0);
});

test('menghapus item dari keranjang', function () {
    $produk = keranjangBuatProduk();

    $this->keranjang->tambah($produk->id);
    $this->keranjang->hapus($produk->id);

    expect($this->keranjang->count())->toBe(0);
});

test('menghitung total belanja', function () {
    $a = keranjangBuatProduk(harga: 100000);
    $b = keranjangBuatProduk(harga: 50000);

    $this->keranjang->tambah($a->id, 2);
    $this->keranjang->tambah($b->id, 1);

    expect($this->keranjang->total())->toBe(250000.0);
});

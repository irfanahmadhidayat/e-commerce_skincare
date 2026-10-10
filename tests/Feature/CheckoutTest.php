<?php

use App\Models\Produk;
use App\Models\User;
use Livewire\Volt\Volt;

test('checkout menyimpan pesanan dan mengosongkan keranjang', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $produk = Produk::where('slug', 'vitamin-c-serum')->firstOrFail();

    session()->put('keranjang', [$produk->id => 2]);

    $this->actingAs($customer);

    Volt::test('pages.store.checkout')
        ->set('nama_penerima', 'Rania')
        ->set('no_hp', '081234567890')
        ->set('alamat', 'Jl. Test No. 1')
        ->call('simpan')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pesanan', [
        'customer_id' => $customer->id,
        'status' => 'menunggu pembayaran',
        'nama_penerima' => 'Rania',
    ]);

    $this->assertDatabaseCount('detail_pesanan', 1);
    expect(session('keranjang'))->toBeNull();
});

test('checkout memvalidasi field wajib', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $produk = Produk::where('slug', 'vitamin-c-serum')->firstOrFail();

    session()->put('keranjang', [$produk->id => 1]);

    $this->actingAs($customer);

    Volt::test('pages.store.checkout')
        ->set('nama_penerima', '')
        ->set('alamat', '')
        ->call('simpan')
        ->assertHasErrors(['nama_penerima', 'alamat']);
});

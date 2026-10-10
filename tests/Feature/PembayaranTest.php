<?php

use App\Models\Pesanan;
use App\Models\User;
use Livewire\Volt\Volt;

test('customer dapat mengirim konfirmasi pembayaran manual', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $pesanan = Pesanan::create([
        'customer_id' => $customer->id,
        'nomor' => 'ARL-TEST-BAYAR',
        'tanggal' => now(),
        'status' => 'menunggu pembayaran',
        'subtotal' => 100000,
        'ongkir' => 0,
        'total' => 100000,
        'nama_penerima' => 'Rania',
        'no_hp' => '081234567890',
        'alamat' => 'Jl. Test No. 1',
    ]);

    $this->actingAs($customer);

    Volt::test('pages.store.pembayaran', ['pesanan' => $pesanan])
        ->set('nama_pengirim', 'Rania')
        ->set('rekening_pengirim', '1234567890')
        ->set('nomor_rekening', '9876543210')
        ->set('jumlah', '100000')
        ->set('tanggal_bayar', now()->toDateString())
        ->call('simpanKonfirmasi')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pembayaran', [
        'pesanan_id' => $pesanan->id,
        'status' => 'menunggu',
    ]);
});

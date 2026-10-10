<?php

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Livewire\Volt\Volt;

test('store home page renders', function () {
    $this->seed();

    $this->get('/')
        ->assertOk()
        ->assertSee('Selamat Datang di Toko Skincare')
        ->assertSee('Keranjang');
});

test('catalog page renders and filters by kategori query', function () {
    $this->seed();

    $this->get('/katalog?kategori=serum')
        ->assertOk()
        ->assertSee('Vitamin C Serum')
        ->assertDontSee('Hydrating Moisturizer');
});

test('catalog page shows all products without filter', function () {
    $this->seed();

    $this->get('/katalog')
        ->assertOk()
        ->assertSee('Vitamin C Serum')
        ->assertSee('Foaming Cleanser');
});

test('catalog with empty kategori dropdown shows all products', function () {
    $this->seed();

    Volt::test('pages.store.catalog')
        ->set('kategori', '')
        ->assertSee('Vitamin C Serum')
        ->assertSee('Foaming Cleanser');
});

test('product detail page renders by slug', function () {
    $this->seed();

    $this->get('/produk/vitamin-c-serum')
        ->assertOk()
        ->assertSee('Vitamin C Serum');
});

test('store home page renders logout button for authenticated user', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();

    $this->actingAs($customer)
        ->get('/')
        ->assertOk()
        ->assertSee('Keluar');
});

test('cart page renders when empty', function () {
    $this->get('/keranjang')
        ->assertOk()
        ->assertSee('Keranjangmu kosong');
});

test('authenticated user can add product to cart from detail page', function () {
    $this->seed();

    $produk = Produk::where('slug', 'vitamin-c-serum')->firstOrFail();
    $customer = User::where('email', 'customer@example.com')->firstOrFail();

    $this->actingAs($customer);

    Volt::test('pages.store.show', ['produk' => $produk])
        ->set('jumlah', 2)
        ->call('tambahKeKeranjang')
        ->assertHasNoErrors();

    expect(session('keranjang'))->toBe([$produk->id => 2]);
});

test('cart badge shows cart count from session', function () {
    session()->put('keranjang', [1 => 3]);

    Volt::test('store-cart-badge')->assertSee('3');
});

test('checkout page requires login', function () {
    $this->get('/checkout')->assertRedirect(route('login'));
});

test('authenticated customer can open checkout page with cart', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $produk = Produk::where('slug', 'vitamin-c-serum')->firstOrFail();

    session()->put('keranjang', [$produk->id => 2]);

    $this->actingAs($customer)
        ->get('/checkout')
        ->assertOk()
        ->assertSee('Checkout');
});

test('pesanan sukses page renders by nomor for authenticated user', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $pesanan = Pesanan::create([
        'customer_id' => $customer->id,
        'nomor' => 'ARL-TEST-001',
        'tanggal' => now(),
        'status' => 'menunggu pembayaran',
        'subtotal' => 100000,
        'ongkir' => 0,
        'total' => 100000,
        'nama_penerima' => 'Test',
        'no_hp' => '0812345678',
        'alamat' => 'Alamat Test',
    ]);

    $this->actingAs($customer)
        ->get('/pesanan/ARL-TEST-001/sukses')
        ->assertOk()
        ->assertSee('ARL-TEST-001');
});

test('pembayaran page renders by nomor for authenticated user', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $pesanan = Pesanan::create([
        'customer_id' => $customer->id,
        'nomor' => 'ARL-TEST-002',
        'tanggal' => now(),
        'status' => 'menunggu pembayaran',
        'subtotal' => 100000,
        'ongkir' => 0,
        'total' => 100000,
        'nama_penerima' => 'Test',
        'no_hp' => '0812345678',
        'alamat' => 'Alamat Test',
    ]);

    $this->actingAs($customer)
        ->get('/pesanan/ARL-TEST-002/bayar')
        ->assertOk()
        ->assertSee('Pembayaran');
});

test('pesanan history page renders orders for the customer', function () {
    $this->seed();

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    Pesanan::create([
        'customer_id' => $customer->id,
        'nomor' => 'ARL-TEST-003',
        'tanggal' => now(),
        'status' => 'menunggu pembayaran',
        'subtotal' => 100000,
        'ongkir' => 0,
        'total' => 100000,
        'nama_penerima' => 'Test',
        'no_hp' => '0812345678',
        'alamat' => 'Alamat Test',
    ]);

    $this->actingAs($customer)
        ->get('/pesanan')
        ->assertOk()
        ->assertSee('Pesanan Saya')
        ->assertSee('ARL-TEST-003');
});

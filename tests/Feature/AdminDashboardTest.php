<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin can access the admin dashboard', function () {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk();
});

test('customer cannot access the admin dashboard', function () {
    Role::create(['name' => 'customer']);

    $customer = User::factory()->create();
    $customer->assignRole('customer');

    $this->actingAs($customer)
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('admin can access every admin index placeholder page', function (string $uri) {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get($uri)
        ->assertOk();
})->with([
    'produk' => '/admin/produk',
    'kategori' => '/admin/kategori',
    'toko' => '/admin/toko',
    'pesanan' => '/admin/pesanan',
    'pengguna' => '/admin/pengguna',
]);

test('guest is redirected to the login page', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect(route('login'));
});

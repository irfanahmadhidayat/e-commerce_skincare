<?php

use App\Models\User;

test('database seeder creates roles, users, categories and products', function () {
    $this->seed();

    $this->assertDatabaseCount('roles', 2);
    $this->assertDatabaseHas('kategori', ['slug' => 'serum']);
    $this->assertDatabaseHas('kategori', ['slug' => 'moisturizer']);
    $this->assertDatabaseHas('kategori', ['slug' => 'cleanser']);
    $this->assertDatabaseCount('produk', 4);

    $admin = User::where('email', 'admin@arlova.my.id')->firstOrFail();
    $this->assertTrue($admin->hasRole('admin'));

    $customer = User::where('email', 'customer@example.com')->firstOrFail();
    $this->assertTrue($customer->hasRole('customer'));
});

<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

test('admin role user passes the admin gate', function () {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    expect(Gate::forUser($admin)->allows('admin'))->toBeTrue();
});

test('non-admin role user does not pass the admin gate', function () {
    Role::create(['name' => 'customer']);

    $customer = User::factory()->create();
    $customer->assignRole('customer');

    expect(Gate::forUser($customer)->allows('admin'))->toBeFalse();
});

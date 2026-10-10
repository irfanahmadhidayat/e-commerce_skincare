<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register', function () {
    Role::create(['name' => 'customer']);

    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->firstOrFail();
    $this->assertTrue($user->hasRole('customer'));
});

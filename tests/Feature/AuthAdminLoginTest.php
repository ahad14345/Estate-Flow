<?php

use Illuminate\Support\Facades\Artisan;

it('allows admin login with configured credentials', function () {
    Artisan::call('migrate:fresh', ['--database' => 'sqlite', '--force' => true]);

    putenv('ADMIN_EMAIL=admin@example.com');
    putenv('ADMIN_PASSWORD=Admin@1234');

    $response = $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'Admin@1234',
    ]);

    $response->assertRedirect('/dashboard');
    expect(auth()->user()->email)->toBe('admin@example.com');
    expect(auth()->user()->name)->toBe('Administrator');
});

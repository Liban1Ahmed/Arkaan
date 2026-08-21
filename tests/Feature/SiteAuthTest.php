<?php

use App\Models\User;
use App\Models\SiteAdmin;

it('allows an admin to log in and receive a token', function () {
    $user = User::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password123'),
    ]);

    SiteAdmin::create(['user_id' => $user->id]);

    $response = $this->postJson('/api/site/login', [
        'email' => 'admin@test.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['user', 'token']);
});

it('rejects login with incorrect password', function () {
    $user = User::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password123'),
    ]);

    SiteAdmin::create(['user_id' => $user->id]);

    $response = $this->postJson('/api/site/login', [
        'email' => 'admin@test.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
});

it('rejects login for a user who is not an admin', function () {
    $user = User::factory()->create([
        'email' => 'notadmin@test.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/site/login', [
        'email' => 'notadmin@test.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
});
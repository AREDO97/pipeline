<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;

test('user can create an account', function () {

    // user
    $user = User::factory()->create([
        'role' => 'super_admin',
    ]);
    Sanctum::actingAs($user);
    // register user
    $response = $this->postJson('/api/register', [
        'name' => 'Ivan Joe',
        'email' => 'van@gmail.com',
        'password' => 'ivan256@@',
    ]);

    $response = $this->getjson('/api/users');

    $response->assertStatus(200);
});

// update user info
test('user user can update their email or name', function () {

    // user
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    // register user
    $response = $this->postJson('/api/user/update/1', [
        'name' => 'Ivan Joe',
        'email' => 'van@gmail.com',
    ]);

    $response->assertStatus(200);
});
// other users can't edit someone's info
test('user user can not update other profiles', function () {

    // user
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(3)->create();
    $response = $this->postJson('/api/user/update/3', [
        'name' => 'Ivan Joe',
        'email' => 'van@gmail.com',
    ]);

    $response->assertStatus(403);
});

// admins only soft delete users
test('admins can soft delete users', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create();
    $response = $this->postJson('/api/users/delete/1', [
        'status' => 'suspended',
    ]);

    $response->assertStatus(200);
});

// /api/users/unsuspend/2
// unsuspend users
test('admins can unsuspend users', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create();
    $response = $this->postJson('/api/users/unsuspend/1', [
        'status' => 'active',
    ]);

    $response->assertStatus(200);
});

// view all suspended users
test('admins can view suspended users', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create([
        'status' => 'active',
    ]);
    $response = $this->getJson('/api/users/suspended');

    $response->assertStatus(200);
});

// super admin creates more admins
test('super_admins can create more admins ', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create();
    $response = $this->postJson('/api/users/create_admin/3', [
        'role' => 'admin',
    ]);

    $response->assertStatus(200);
});

// super admin demotes admins to normal users
test('super_admins can demote other admins ', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create([
        'role' => 'admin',
    ]);
    $response = $this->postJson('/api/users/demote_admin/3', [
        'role' => 'user',
    ]);

    $response->assertStatus(200);
});

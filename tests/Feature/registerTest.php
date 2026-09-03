<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
uses(RefreshDatabase::class);
use App\Models\Inquiry;
use App\Models\User;

test('user can create an account', function () {

    $response = $this->postJson('/api/register',[
        'name'=>'Ivan Joe',
        'email'=>'van@gmail.com',
        'password'=>"ivan256@@"
    ]);

    $response->assertStatus(201);
});

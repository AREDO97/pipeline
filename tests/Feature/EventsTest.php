<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
uses(RefreshDatabase::class);
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('admins can create an event', function () {

    // user
    $user=User::factory()->create([
        'role'=>'super_admin'
    ]);
    Sanctum::actingAs($user);
    // register user
    $response = $this->postJson('/api/event/create',[
        'user_id'=>$user->id,
        'title'=>'Ivan Joe',
        'description'=>'van@gmail.com',
        'date'=>"2026-09-03 12:31:28",
        'image'=>UploadedFile::fake()->image('events.png')
    ]);


    $response->assertStatus(201);
});

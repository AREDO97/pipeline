<?php

use App\Models\Event;
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

// delete events
test('admins can delete an event', function () {

    // user
    $user=User::factory()->create([
        'role'=>'super_admin'
    ]);
    Sanctum::actingAs($user);
   // create event
    $events=Event::factory()->count(10)->create();
    $response = $this->deleteJson('/api/event/1/delete',[
      'status'=>'deleted'
    ]);

    $response->assertStatus(200);
});

// access all events
test('users can acces events', function () {

    // user
    $user=User::factory()->create();
    Sanctum::actingAs($user);
   // create event
    $events=Event::factory()->count(10)->create();
    $response = $this->getJson('/api/events');

    $response->assertStatus(200);
});
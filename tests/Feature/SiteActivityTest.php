<?php

use App\Models\User;
use App\Models\SiteAdmin;
use App\Models\SiteActivity;

function loginAsAdmin($test)
{
    $user = User::factory()->create();
    SiteAdmin::create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;
    return $token;
}

it('lists all activities publicly without authentication', function () {
    SiteActivity::factory()->count(3)->create();

    $response = $this->getJson('/api/site/activities');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('rejects activity creation without authentication', function () {
    $response = $this->postJson('/api/site/activities', [
        'title' => 'Test Event',
        'description' => 'Test Description',
    ]);

    $response->assertStatus(401);
});

it('allows an admin to create an activity', function () {
    $token = loginAsAdmin($this);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/site/activities', [
            'title' => 'Youth Night',
            'description' => 'Weekly gathering',
            'category' => 'Weekly',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('title', 'Youth Night');

    $this->assertDatabaseHas('site_activities', [
        'title' => 'Youth Night',
    ]);
});

it('allows an admin to update an activity', function () {
    $token = loginAsAdmin($this);
    $activity = SiteActivity::factory()->create(['title' => 'Old Title']);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/site/activities/{$activity->id}", [
            'title' => 'New Title',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('title', 'New Title');

    $this->assertDatabaseHas('site_activities', [
        'id' => $activity->id,
        'title' => 'New Title',
    ]);
});

it('allows an admin to delete an activity', function () {
    $token = loginAsAdmin($this);
    $activity = SiteActivity::factory()->create();

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/api/site/activities/{$activity->id}");

    $response->assertStatus(200);

    $this->assertDatabaseMissing('site_activities', [
        'id' => $activity->id,
    ]);
});
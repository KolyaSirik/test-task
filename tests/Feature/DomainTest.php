<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_domains()
    {
        $this->get(route('domains.index'))->assertRedirect(route('login'));
        $this->get(route('domains.create'))->assertRedirect(route('login'));
    }

    public function test_user_can_see_only_their_domains()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $domain1 = Domain::factory()->create(['user_id' => $user1->id, 'url' => 'https://user1.com']);
        $domain2 = Domain::factory()->create(['user_id' => $user2->id, 'url' => 'https://user2.com']);

        $this->actingAs($user1);
        
        $this->get(route('domains.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('domains/Index')
                ->has('domains', 1)
                ->where('domains.0.url', 'https://user1.com')
            );
    }

    public function test_user_can_create_domain()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('domains.store'), [
            'url' => 'https://google.com',
            'check_interval' => 10,
            'request_timeout' => 5,
            'check_method' => 'GET',
        ]);

        $response->assertRedirect(route('domains.index'));
        $this->assertDatabaseHas('domains', [
            'user_id' => $user->id,
            'url' => 'https://google.com',
            'check_interval' => 10,
        ]);
    }

    public function test_user_can_update_their_domain()
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->put(route('domains.update', $domain), [
            'url' => 'https://updated.com',
            'check_interval' => 15,
            'request_timeout' => 10,
            'check_method' => 'HEAD',
        ]);

        $response->assertRedirect(route('domains.index'));
        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
            'url' => 'https://updated.com',
            'check_method' => 'HEAD',
        ]);
    }

    public function test_user_cannot_update_others_domain()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user2->id]);

        $this->actingAs($user1);
        $response = $this->put(route('domains.update', $domain), [
            'url' => 'https://hacker.com',
            'check_interval' => 5,
            'request_timeout' => 5,
            'check_method' => 'GET',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_domain()
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->delete(route('domains.destroy', $domain));

        $response->assertRedirect(route('domains.index'));
        $this->assertDatabaseMissing('domains', ['id' => $domain->id]);
    }

    public function test_user_can_mark_notifications_as_read()
    {
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        
        // Trigger a notification manually or via factory (if available)
        $user->notify(new \App\Notifications\DomainStatusChanged($domain));
        
        $this->assertEquals(1, $user->unreadNotifications()->count());

        $this->actingAs($user);
        $response = $this->post(route('notifications.mark-as-read'));

        $response->assertRedirect();
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\User;
use App\Notifications\DomainStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DomainMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_domains_command_checks_available_domains()
    {
        Http::fake([
            'google.com*' => Http::response([], 200),
            'broken.com*' => Http::response([], 500),
        ]);

        $user = User::factory()->create();
        $domain1 = Domain::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://google.com',
            'last_checked_at' => null,
        ]);
        $domain2 = Domain::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://broken.com',
            'last_checked_at' => null,
        ]);

        $this->artisan('app:check-domains')->assertExitCode(0);

        $this->assertDatabaseHas('domains', [
            'id' => $domain1->id,
            'is_up' => true,
        ]);
        $this->assertDatabaseHas('domains', [
            'id' => $domain2->id,
            'is_up' => false,
        ]);

        $this->assertCount(1, $domain1->checks);
        $this->assertCount(1, $domain2->checks);
    }

    public function test_monitoring_command_respects_intervals()
    {
        Http::fake();
        $user = User::factory()->create();
        
        // This one should be checked (last checked 10 mins ago, interval 5)
        $domain1 = Domain::factory()->create([
            'user_id' => $user->id,
            'check_interval' => 5,
            'last_checked_at' => now()->subMinutes(10),
        ]);

        // This one should NOT be checked (last checked 2 mins ago, interval 5)
        $domain2 = Domain::factory()->create([
            'user_id' => $user->id,
            'check_interval' => 5,
            'last_checked_at' => now()->subMinutes(2),
        ]);

        $this->artisan('app:check-domains');

        $this->assertNotEquals($domain1->fresh()->last_checked_at->timestamp, $domain1->last_checked_at->timestamp);
        $this->assertEquals($domain2->fresh()->last_checked_at->timestamp, $domain2->last_checked_at->timestamp);
    }

    public function test_it_sends_notification_when_status_changes()
    {
        Notification::fake();
        Http::fake([
            'test.com*' => Http::response([], 500), // Change to DOWN
        ]);

        $user = User::factory()->create();
        $domain = Domain::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://test.com',
            'is_up' => true, // Currently UP
            'last_checked_at' => now()->subMinutes(10),
        ]);

        $this->artisan('app:check-domains');

        Notification::assertSentTo(
            $user,
            DomainStatusChanged::class,
            function ($notification, $channels) use ($domain) {
                return $notification->domain->id === $domain->id;
            }
        );
    }

    public function test_it_handles_timeouts_and_errors()
    {
        Http::fake([
            'timeout.com*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
            },
        ]);

        $user = User::factory()->create();
        $domain = Domain::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://timeout.com',
        ]);

        $this->artisan('app:check-domains');

        $this->assertDatabaseHas('domains', [
            'id' => $domain->id,
            'is_up' => false,
        ]);
        
        $lastCheck = $domain->checks()->latest()->first();
        $this->assertNotNull($lastCheck->error_message);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Notifications\DomainStatusChanged;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('app:check-domains')]
#[Description('Command description')]
class CheckDomains extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $domains = Domain::query()
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhereRaw('datetime(last_checked_at, "+" || check_interval || " minutes") <= datetime("now")');
            })
            ->get();

        foreach ($domains as $domain) {
            $this->checkDomain($domain);
        }
    }

    protected function checkDomain(Domain $domain): void
    {
        $startTime = microtime(true);
        $isSuccessful = false;
        $statusCode = null;
        $errorMessage = null;

        try {
            $response = Http::timeout($domain->request_timeout)
                ->{strtolower($domain->check_method)}($domain->url);

            $statusCode = $response->status();
            $isSuccessful = $response->successful();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $responseTime = round((microtime(true) - $startTime) * 1000);

        $domain->checks()->create([
            'status_code' => $statusCode,
            'response_time' => $responseTime,
            'is_successful' => $isSuccessful,
            'error_message' => $errorMessage,
            'created_at' => now(),
        ]);

        $oldStatus = $domain->is_up;
        $domain->update([
            'is_up' => $isSuccessful,
            'last_checked_at' => now(),
        ]);

        if ($oldStatus !== null && $oldStatus !== $isSuccessful) {
            $domain->user->notify(new DomainStatusChanged($domain));
        }
    }
}

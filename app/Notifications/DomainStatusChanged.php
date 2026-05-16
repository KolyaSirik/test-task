<?php

namespace App\Notifications;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Domain $domain)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->domain->is_up ? 'UP' : 'DOWN';

        return (new MailMessage)
            ->subject("Domain status changed: {$this->domain->url}")
            ->line("The status of your domain {$this->domain->url} has changed to {$status}.")
            ->action('View Domain', route('domains.show', $this->domain))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'domain_id' => $this->domain->id,
            'url' => $this->domain->url,
            'is_up' => $this->domain->is_up,
            'message' => "Domain {$this->domain->url} is now " . ($this->domain->is_up ? 'UP' : 'DOWN'),
        ];
    }
}

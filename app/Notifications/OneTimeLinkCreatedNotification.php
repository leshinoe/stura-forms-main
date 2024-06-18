<?php

namespace App\Notifications;

use App\Models\OneTimeToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OneTimeLinkCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected OneTimeToken $oneTimeToken
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->replyTo('office@stura-btu.de', 'StuRa BTU Forms')
            ->subject(__('stura.notifications.one_time_link_created.subject'))
            ->greeting(__('stura.notifications.one_time_link_created.greeting', [
                'name' => $notifiable->name,
            ]))
            ->line(__('stura.notifications.one_time_link_created.line_1'))
            ->action(__('stura.notifications.one_time_link_created.action'), $this->oneTimeLink())
            ->line(__('stura.notifications.one_time_link_created.line_2', [
                'expires_at' => $this->oneTimeToken->expires_at->format('d.m.Y'),
            ]))
            ->salutation(__('stura.notifications.one_time_link_created.salutation'));
    }

    protected function oneTimeLink(): string
    {
        return route('requests.dticket', [
            'token' => $this->oneTimeToken->token,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

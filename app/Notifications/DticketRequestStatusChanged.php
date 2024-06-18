<?php

namespace App\Notifications;

use App\Models\Dticket\DticketRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DticketRequestStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected DticketRequest $dticketRequest,
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
            ->replyTo('semesterticket@stura-btu.de', 'StuRa BTU Semesterticket')
            ->subject(__('stura.notifications.status_changed.subject'))
            ->greeting(__('stura.notifications.status_changed.greeting', [
                'name' => $notifiable->name,
            ]))
            ->line(__('stura.notifications.status_changed.line_1', [
                'status' => $this->dticketRequest->statusLabel(),
            ]))
            ->action(__('stura.notifications.status_changed.action'), $this->viewDticketRequestUrl())
            ->line(__('stura.notifications.status_changed.line_2'))
            ->line(__('stura.notifications.status_changed.line_3'))
            ->salutation(__('stura.notifications.status_changed.salutation'));
    }

    protected function viewDticketRequestUrl(): string
    {
        return route('requests.dticket.show', $this->dticketRequest);
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

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DueReturnNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $body;

    public function __construct(string $title, string $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable)
    {
        // Use database + mail if email is present
        return ['database','mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->body)
            ->action('Open TaxEase', url('/'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $icon;
    public $type;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string $icon (Bootstrap icon class, e.g., 'bi-info-circle')
     * @param string $type ('info', 'success', 'warning', 'danger')
     * @param string|null $link (Optional link to redirect to)
     */
    public function __construct($title, $message, $icon = 'bi-bell', $type = 'info', $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->type = $type;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'type' => $this->type,
            'link' => $this->link,
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
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'type' => $this->type,
            'link' => $this->link,
        ];
    }
}

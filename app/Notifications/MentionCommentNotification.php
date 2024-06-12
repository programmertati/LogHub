<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Card;
use DB;

class MentionCommentNotification extends Notification
{
    use Queueable;
    public $user;
    public $content;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $content)
    {
        $this->user = $user;
        $this->content = $content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name'      => $this->user->name,
            'avatar'    => $this->user->avatar,
            'message'   => 'Mention Tag Comment',
            'message2'  => 'LogHub Apps',
            'message3'  => $this->content
        ];
    }
}
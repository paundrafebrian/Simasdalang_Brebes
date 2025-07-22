<?php

namespace App\Notifications;

use App\Models\KanbanComment;
use Illuminate\Notifications\Notification;

class MentionedInKanbanCommentNotification extends Notification
{
    protected $kanbanComment;

    public function __construct(KanbanComment $kanbanComment)
    {
        $this->kanbanComment = $kanbanComment;
    }

    public function via($notifiable)
    {
        return ['database']; // Gunakan database untuk menyimpan notifikasi
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Anda disebutkan dalam komentar: {$this->kanbanComment->comment}",
            'kanban_comment_id' => $this->kanbanComment->id,
        ];
    }
}

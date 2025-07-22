<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ TAMBAHKAN INI

class CommentMention extends Model
{
    use HasFactory;

    protected $fillable = ['kanban_comment_id', 'mentioned_user_id'];

    public function kanbanComment()
    {
        return $this->belongsTo(KanbanComment::class,  'kanban_comment_id');
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }
}

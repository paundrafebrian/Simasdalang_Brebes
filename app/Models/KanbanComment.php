<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kanban_card_id',
        'user_id',
        'comment',
    ];

    public function card()
    {
        return $this->belongsTo(KanbanCard::class, 'kanban_card_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function mentions()
    {
        return $this->hasMany(CommentMention::class, 'kanban_comment_id');
    }
}

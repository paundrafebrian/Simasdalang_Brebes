<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'kanban_column_id',
        'title',
        'notes',
        'order',
        'user_id',
    ];


    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function column()
    {
        return $this->belongsTo(KanbanColumn::class, 'kanban_column_id');
    }

    public function comments()
    {
        return $this->hasMany(KanbanComment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


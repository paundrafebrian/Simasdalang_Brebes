<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'photo',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function kanbanCards()
    {
        return $this->hasMany(KanbanCard::class);
    }

    public function kanbanColumns()
    {
        return $this->hasMany(KanbanColumn::class);
    }

}

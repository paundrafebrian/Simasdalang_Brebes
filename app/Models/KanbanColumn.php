<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $fillable = ['activity_id', 'name', 'order'];

    public function kanbanCards()
    {
        return $this->hasMany(KanbanCard::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment_from',
        'comment_to',
        'comment_message',
        'comment_type',
        'comment_date',
        'comment_part_id',
        'comment_level',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'comment_from');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'comment_to');
    }

    // If comment_part_id refers to a requisition
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'comment_part_id');
    }
}

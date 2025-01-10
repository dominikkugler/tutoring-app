<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Define the table name (optional, if it follows Laravel's convention)
    protected $table = 'messages';

    // Define which fields can be mass assigned
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
    ];

    // Relationship with the sender (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship with the recipient (User)
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}

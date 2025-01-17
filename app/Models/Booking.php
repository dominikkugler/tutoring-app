<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Define the table associated with the model if it's not the default 'bookings'
    protected $table = 'bookings';

    // Define the fillable properties (which can be mass assigned)
    protected $fillable = [
        'user_id', // student who created the booking
        'tutor_id', // tutor the booking is about
        'category_id', // chosen subject category
        'date', // booking date
        'start_hour', // booking start hour
        'end_hour', // booking end hour
        'status', // booking status (pending, rejected, accepted)
    ];

    // Define relationships

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

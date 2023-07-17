<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    use HasFactory;
    protected $table = 'rsvps';

    /**
     * The attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'event_id',
        'event_type',
        'user_id',
        'full_name',
        'school_name',
        'number_of_guests'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event(){
        return $this->belongsTo(Event::class);
    }
    public function eventtype(){
        return $this->belongsTo(Eventtype::class);
    }
    public function school(){
        return $this->belongsTo(School::class);
    }

}

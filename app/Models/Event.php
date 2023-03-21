<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'event_title',
        'event_content',
        'event_excerpt',
        'start_date',
        'end_date',
        'event_status',
        'eventtype_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function eventtype()
    {
        return $this->belongsTo(Eventtype::class);
    }
}

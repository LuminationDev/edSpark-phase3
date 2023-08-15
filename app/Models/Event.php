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
        'event_location',
        'start_date',
        'end_date',
        'event_status',
        'cover_image',
        'eventtype_id',
        'extra_content',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function eventtype()
    {
        return $this->belongsTo(Eventtype::class);
    }
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'event');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'event');
    }

    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array',
    ];
}

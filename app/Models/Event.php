<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Event extends Model
{
    use HasFactory, HasTags, Searchable;

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
        'event_format_id',
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
    public function eventformat()
    {
        return $this->belongsTo(Eventformat::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'event');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'event');
    }

    public function getSearchResult()
    {
        return [
            'title' => $this->event_title,
            'content' => strip_tags($this->event_content),
            'tags' => $this->tags,
            'author' => [
                'author_id' => $this->author->id ?? '',
                'author_name' => $this->author->full_name ?? '',
                'author_type' => $this->author->usertype->user_type_name ?? '',
            ],
        ];
    }
    public function isActive(): bool
    {
        // Check if the end date is in the future
        return now()->lessThan($this->end_date);
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->event_title,
            'slug' => $this->event_title,
            'content' => $this->event_content,
        ];
    }

    protected $with = ['tags'];
    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            if ($event->extra_content) {
                $event->extra_content = ExtraContentCleaner::cleanExtraContent($event->extra_content);
            }
        });
        static::updating(function ($event) {
            if ($event->isDirty('extra_content')) {
                $event->extra_content = ExtraContentCleaner::cleanExtraContent($event->extra_content);
            }
        });
    }
    public function labels()
    {
        return $this->morphToMany(Label::class, 'labellable');
    }
}

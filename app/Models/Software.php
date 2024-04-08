<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Software extends Model
{
    use
        HasFactory, HasTags;
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'softwares';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'excerpt',
        'cover_image',
        'extra_content',
        'how_to_access',
        'status',
        'created_at',
        'modified_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function software_types()
    {
        return $this->belongsToMany(Softwaretype::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'software');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'software');
    }

    public function getSearchResult()
    {
        return [
            'title' => $this->title,
            'content' => strip_tags($this->content),
            'tags' => $this->tags,
            'author' => [
                'author_id' => $this->author->id ?? '',
                'author_name' => $this->author->full_name ?? '',
                'author_type' => $this->author->usertype->user_type_name ?? '',
            ],
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->title,
            'content' => $this->content,
        ];
    }

    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($software) {
            if ($software->extra_content) {
                $software->extra_content = ExtraContentCleaner::cleanExtraContent($software->extra_content);
            }
        });
        static::updating(function ($software) {
            if ($software->isDirty('extra_content')) {
                $software->extra_content = ExtraContentCleaner::cleanExtraContent($software->extra_content);
            }
        });
    }
    public function labels()
    {
        return $this->morphToMany(Label::class, 'labellable');
    }
}

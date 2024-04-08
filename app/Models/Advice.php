<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Advice extends Model
{
    use HasFactory, HasTags, Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advices';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'status',
        'author_id',
        'cover_image',
        'advicetype_id',
        'template',
        'extra_content',
        'created_at',
        'modified_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

//    public function advicetype()
//    {
//        return $this->belongsTo(Advicetype::class);
//    }

    public function advice_types()
    {
        return $this->belongsToMany(Advicetype::class);
    }


    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'advice');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'advice');
    }

    public function getSearchResult() {
        return [
            'title' => $this->title,
            'content' => strip_tags($this->content),
            'tags' => $this->tags,
            'author' =>[
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
    protected $with = ['tags'];
    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($advice) {
            if ($advice->extra_content) {
                $advice->extra_content = ExtraContentCleaner::cleanExtraContent($advice->extra_content);
            }
        });
        static::updating(function ($advice) {
            if ($advice->isDirty('extra_content')) {
                $advice->extra_content = ExtraContentCleaner::cleanExtraContent($advice->extra_content);
            }
        });
    }

    public function labels()
    {
        return $this->morphToMany(Label::class, 'labellable');
    }
    public function syncLabels(string | array | \ArrayAccess $labels): static
    {
        if (is_string($labels)) {
            $labels = \Illuminate\Support\Arr::wrap($labels);
        }

        $labels = collect(Label::findOrCreate($labels));

        $this->labels()->sync($labels->pluck('id')->toArray());

        return $this;
    }
}

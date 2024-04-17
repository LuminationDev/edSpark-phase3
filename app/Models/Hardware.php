<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Hardware extends Model
{
    use HasFactory, HasTags, Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hardwares';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'name',
        'content',
        'excerpt',
        'price',
        'SKU',
        'cover_image',
        'gallery',
        'extra_content',
        'brand_id',
        'category_id',
        'created_at',
        'updated_at'

    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Productbrand::class);
    }

    public function category()
    {
        return $this->belongsTo(Productcategory::class);
    }

    // public function inventory()
    // {
    //     return $this->belongsTo(Productinventory::class);
    // }
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'hardware');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'hardware');
    }
    public function getSearchResult() {
        return [
            'title' => $this->product_name,
            'content' => strip_tags($this->product_content),
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
            'title' => $this->product_name,
            'slug' => $this->product_name,
            'content' => $this->product_content,
        ];
    }
    protected $casts = [
        'cover_image' => 'array',
        'gallery' => 'array',
        'extra_content' => 'array'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($hardware) {
            if ($hardware->extra_content) {
                $hardware->extra_content = ExtraContentCleaner::cleanExtraContent($hardware->extra_content);
            }
        });
        static::updating(function ($hardware) {
            if ($hardware->isDirty('extra_content')) {
                $hardware->extra_content = ExtraContentCleaner::cleanExtraContent($hardware->extra_content);
            }
        });
    }
    public function labels()
    {
        return $this->morphToMany(Label::class, 'labellable');
    }

}

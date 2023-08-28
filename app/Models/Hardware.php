<?php

namespace App\Models;

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
        'product_name',
        'product_content',
        'product_excerpt',
        'price',
        'product_SKU',
        'created',
        'modified',
        'cover_image',
        'gallery',
        'product_isLoan',
        'template',
        'extra_content'

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

}

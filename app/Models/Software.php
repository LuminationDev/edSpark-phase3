<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

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
        'post_title',
        'post_content',
        'post_excerpt',
        'post_date',
        'post_modified',
        'post_status',
        'author_id',
        'cover_image',
        'softwaretype_id',
        'template',
        'extra_content'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function softwaretypes()
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

    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'communities';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'post_title',
        'post_content',
        'excerpt',
        'status',
        'created_at',
        'post_modified',
        'status',
        'cover_image',
        'communitytype_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function communitytype()
    {
        return $this->belongsTo(Communitytype::class);
    }

    protected $casts = [
        'cover_image' => 'array',
    ];

}

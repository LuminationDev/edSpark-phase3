<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Advicemoderation extends Model
{
    use HasFactory;

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
        'post_title',
        'post_content',
        'post_excerpt',
        'post_status',
        'post_date',
        'post_modified',
        'author_id',
        'cover_image',
        'advicetype_id',
        'template',
        'extra_content'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advice extends Model
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

    public function author()
    {
        return $this->belongsTo(User::class);
    }

//    public function advicetype()
//    {
//        return $this->belongsTo(Advicetype::class);
//    }

    public function advicetypes()
    {
        return $this->belongsToMany(Advicetype::class);
    }

    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array',
    ];

}

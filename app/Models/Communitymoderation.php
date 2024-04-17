<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communitymoderation extends Model
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
        'post_excerpt',
        'status',
        'created_at',
        'modified_at',
        'status',
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
}

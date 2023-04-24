<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table assocaited with the model.
     *
     * @var string
     */
    protected $table = 'bookmarks';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'post_type',
        'user_id'
    ];


}

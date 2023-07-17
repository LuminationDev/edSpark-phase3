<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'likes';


    /**
     * The attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'post_id',
        'post_type',
        'user_id'
    ];
}

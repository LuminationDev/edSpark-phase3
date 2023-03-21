<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communitytype extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'community_types';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'community_type_name',
        'community_type_value'
    ];

}

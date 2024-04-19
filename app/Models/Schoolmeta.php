<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolmeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_metas';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'meta_key',
        'meta_value'
    ];
}

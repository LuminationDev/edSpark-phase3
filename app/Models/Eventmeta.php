<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventmeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_metas';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'meta_key',
        'meta_value'
    ];
}

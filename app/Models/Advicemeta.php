<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advicemeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advice_metas';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillabel = [
        'advice_id',
        'advice_meta_key',
        'advice_meta_value'
    ];
}

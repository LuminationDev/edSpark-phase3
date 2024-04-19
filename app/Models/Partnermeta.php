<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnermeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partner_metas';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'partner_id',
        'meta_key',
        'meta_value'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sites';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'site_uid',
        'site_id',
        'site_name',
        'site_value',
        'category_code',
        'category_desc',
        'site_type_code',
        'site_type_desc',
        'site_sub_type_code',
        'site_sub_type_desc',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_info';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'full_name',
        'description',
        'cover',
        'tech_used',
        'school_content',
        'created_at',
        'updated_at',
    ];
}

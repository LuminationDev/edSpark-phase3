<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Softwaretype extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'software_types';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'software_type_name',
        'software_type_value'
    ];

}

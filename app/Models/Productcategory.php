<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productcategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hardware_categories';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
        'category_description',
    ];
}

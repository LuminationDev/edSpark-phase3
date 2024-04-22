<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productbrand extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hardware_brands';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'brand_name',
        'brand_description',
    ];
}

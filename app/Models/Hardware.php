<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hardware extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hardwares';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'product_name',
        'product_content',
        'product_excerpt',
        'price',
        'product_SKU',
        'created',
        'modified',
        'cover_image',
        'gallery',
        'product_isLoan'

    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Productbrand::class);
    }

    public function category()
    {
        return $this->belongsTo(Productcategory::class);
    }

    // public function inventory()
    // {
    //     return $this->belongsTo(Productinventory::class);
    // }

    protected $casts = [
        'cover_image' => 'array',
        'gallery' => 'array',
    ];

}

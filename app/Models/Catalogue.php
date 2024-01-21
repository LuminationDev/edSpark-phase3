<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static excludeBundles()
 */
class Catalogue extends Model
{
    use HasFactory;
    protected $table = 'catalogues';
    protected $fillable = [
        'unique_reference', 'type', 'brand', 'name', 'vendor', 'category', 'price_inc_gst',
        'processor', 'storage', 'memory', 'form_factor', 'display', 'graphics',
        'wireless', 'webcam', 'operating_system', 'warranty', 'battery_life',
        'weight', 'stylus', 'other', 'available_now', 'corporate', 'administration',
        'curriculum', 'image', 'product_number', 'price_expiry',
    ];

    public function scopeExcludeBundles($query)
    {
        return $query->whereNotIn('category', ['bundle']);
    }

    public function getNameAttribute($value)
    {
        return trim($value);
    }

    public function getVendorAttribute($value)
    {
        return trim($value);
    }

    public function getBrandAttribute($value){
        return trim($value);
    }
}

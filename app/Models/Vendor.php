<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor',
        'business_name',
        'abn',
        'email_enquiries',
        'name',
        'phone',
        'phone_general_enquiries',
        'fax',
        'address',
        'postal_address',
        'website',
        'portal',
        'email_orders',
        'warranty_support_info',
        'buyers_guide',
        'comments',
        'confirmed',
    ];
}

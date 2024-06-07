<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'version_id', 'quote_content','quote_ref',
        'total_price_ex_gst', 'status', 'delivery_info','pdf_url'
    ];

    protected $casts = [
        'quote_content' => 'json',
        'total_price_ex_gst' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(CatalogueVersion::class, 'version_id', 'version');
    }
}

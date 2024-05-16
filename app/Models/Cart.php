<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'version_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(CatalogueVersion::class, 'version_id', 'version');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}

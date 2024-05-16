<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'version_id', 'status'];

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

    public static function getUserCart($userId)
    {
        $catalogue_version = Catalogueversion::getActiveCatalogueId();
        $cart = self::where('user_id', $userId)
            ->where('version_id', $catalogue_version)
            ->where('status', 'ACTIVE')
            ->first();

        if ($cart) {
            Log::info('fetched new cart');
            return $cart;
        } else {
            Log::info('create new cart');
            return self::create([
                'user_id' => $userId,
                'version_id' => $catalogue_version,
                'status' => 'ACTIVE'
            ]);
        }
    }
}

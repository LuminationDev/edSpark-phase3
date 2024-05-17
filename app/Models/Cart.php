<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    public function getTotalIncGst()
    {
        $userId = Auth::id();
        $catalogue_version = Catalogueversion::getActiveCatalogueId();
        $cart = self::where('user_id', $userId)
            ->where('version_id', $catalogue_version)
            ->where('status', 'ACTIVE')
            ->first();
        return $cart->cartItems->sum(function($cartItem) {
            return round($cartItem->quantity * $cartItem->catalogue->price_inc_gst,2);
        });
    }

    public function getTotalExGst()
    {
        $userId = Auth::id();
        $catalogue_version = Catalogueversion::getActiveCatalogueId();
        $cart = self::where('user_id', $userId)
            ->where('version_id', $catalogue_version)
            ->where('status', 'ACTIVE')
            ->first();
        return $cart->cartItems->sum(function($cartItem) {
            $price_ext_gst = $cartItem->catalogue->price_inc_gst / 1.1;
            return round($cartItem->quantity * $price_ext_gst,2);
        });
    }
}

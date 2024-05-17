<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Catalogue;
use App\Models\Catalogueversion;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    private function getOrCreateCart($userId, $versionId)
    {
        $cart = Cart::with('cartItems.catalogue')
            ->where('user_id', $userId)
            ->where('version_id', $versionId)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'version_id' => $versionId,
                'status' => 'ACTIVE',
            ]);
        }

        return $cart;
    }

    // GET /cart: Returns the content of the user's cart.
    public function index()
    {
        $user = Auth::user();
        $currentVersionId = Catalogueversion::getActiveCatalogueId();

        $cart = $this->getOrCreateCart($user->id, $currentVersionId);
        $cart_items = $cart->cartItems;
        $result = $cart_items->map(function ($item) {
            $catalogueAttributes = CatalogueController::catalogueModelToJson($item->catalogue);
            $catalogueAttributes['quantity'] = $item->quantity;
            return $catalogueAttributes;
        });

        return response()->json($result, 200);
    }

    // POST /cart: Adds an item to the cart.
    public function store(Request $request)
    {
        $user = Auth::user();
        $unique_reference = $request->input('unique_reference');
        $quantity = $request->input('quantity');
        $catalogue = Catalogue::findActiveItemByReference($unique_reference);

        if (!$catalogue) {
            return response()->json(['message' => 'Catalogue item not found'], 404);
        }

        $cart = Cart::getUserCart($user->id);
        $catalogue_id = $catalogue->id;

        // Check if the cart item already exists
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('catalogue_id', $catalogue_id)
            ->first();

        // If cart item exists, update the quantity
        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'catalogue_id' => $catalogue_id,
                'quantity' => $quantity
            ]);
        }

        return response()->json($cartItem, $cartItem->wasRecentlyCreated ? 201 : 200);
    }


    // PUT /cart/:item_id/update: Increment the count of an item inside the cart.
    public function update(Request $request, $item_id)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity');
        $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->find($item_id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->update(['quantity' => $quantity]);

        return response()->json($cartItem, 200);
    }

    // DELETE /cart/:item_id: Remove a specific item from the cart.
    public function destroyItem($item_id)
    {
        $user = Auth::user();
        $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->find($item_id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart'], 200);
    }

    // DELETE /cart: Clear the entire cart.
    public function clear()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is already empty'], 200);
        }

        $cart->cartItems()->delete();

        return response()->json(['message' => 'Cart cleared'], 200);
    }

    public function total()
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.catalogue')->where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        $total = $cart->cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * $item->catalogue->price);
        }, 0);

        return response()->json(['total' => $total], 200);
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.catalogue')->where('user_id', $user->id)->where('status', 'ACTIVE')->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty or already checked out'], 200);
        }

        $quoteContent = $cart->cartItems->map(function ($item) {
            return [
                'catalogue_id' => $item->catalogue_id,
                'quantity' => $item->quantity,
                'price' => $item->catalogue->price,
                'total' => $item->quantity * $item->catalogue->price,
            ];
        });

        $totalPrice = $quoteContent->sum('total');

        $quote = Quote::create([
            'user_id' => $user->id,
            'version_id' => $cart->version_id,
            'quote_content' => $quoteContent->toArray(),
            'total_price_ex_gst' => $totalPrice,
            'status' => 'COMPLETED',
        ]);

        // Mark the current cart as completed
        $cart->update(['status' => 'COMPLETED']);

        // Create a new active cart for the user
        $newCart = Cart::create([
            'user_id' => $user->id,
            'version_id' => $cart->version_id,
            'status' => 'ACTIVE',
        ]);


        return response()->json(['message' => 'Checkout completed', 'quote' => $quote], 201);
    }
}
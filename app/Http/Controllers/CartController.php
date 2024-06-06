<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Catalogue;
use App\Models\Catalogueversion;
use App\Models\Quote;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Outerweb\ImageLibrary\Models\Image;

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
            $catalogueAttributes['cart_item_id'] = $item->id;

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
    public function update(Request $request, $item_ref)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity');
        $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('catalogue', function ($query) use ($item_ref) {
            $query->where('unique_reference', $item_ref)->where('version_id', Catalogueversion::getActiveCatalogueId());
        })->first();


        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 410);
        }

        $cartItem->update(['quantity' => $quantity]);

        return response()->json($cartItem, 200);
    }

    // DELETE /cart/:item_id: Remove a specific item from the cart.
    public function destroyItem($item_ref)
    {
        $user = Auth::user();
        $cartItem = CartItem::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('catalogue', function ($query) use ($item_ref) {
            $query->where('unique_reference', $item_ref)->where('version_id', Catalogueversion::getActiveCatalogueId());
        })->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 410);
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

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $vendorName = $request->input('vendor');
        $deliveryInfo = $request->input('delivery_info');
        $additionalNotes = $request->input('additional_notes');

        $cart = $this->getActiveCart($user->id);
        if (!$cart) {
            return response()->json(['message' => 'Cart is empty or already checked out'], 200);
        }

        $vendorCartItems = $this->getVendorCartItems($cart, $vendorName);
        if ($vendorCartItems->isEmpty()) {
            return response()->json(['message' => 'No items from the specified vendor in the cart'], 200);
        }

        $quoteContent = $this->generateQuoteContent($vendorCartItems, $cart->id, $additionalNotes);
        $totalPrice = 0;
        foreach ($quoteContent as $item) {
            $totalPrice += $item['total'];
        }

        $quote = $this->createQuote($user->id, $cart->version_id, $quoteContent, $totalPrice, $deliveryInfo);

        $this->removeCheckedOutItems($cart, $vendorCartItems);
        $this->updateCartStatus($cart);

        return response()->json(['message' => 'Checkout completed for vendor', 'quote' => $quote], 201);
    }

    private function getActiveCart($userId)
    {
        return Cart::with('cartItems.catalogue')
            ->where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->first();
    }

    private function getVendorCartItems($cart, $vendorName)
    {
        return $cart->cartItems->filter(function ($item) use ($vendorName) {
            return $item->catalogue->vendor == $vendorName && $item->catalogue->version_id == Catalogueversion::getActiveCatalogueId();
        });
    }

    private function generateQuoteContent($vendorCartItems, $cartId, $additionalNotes)
    {
        return $vendorCartItems->map(function ($item) use ($cartId,$additionalNotes) {
            $itemCatalogue = $item->catalogue;
            $itemImageUUID = '';
            $itemImageExtension = '';

            if ($itemCatalogue->cover_image) {
                $itemImage = Image::where('id', $itemCatalogue->cover_image)->first();
                if ($itemImage) {
                    $itemImageUUID = $itemImage->uuid ?? '';
                    $itemImageExtension = $itemImage->file_extension ?? '';
                }
            }
            $uniqueReference= $itemCatalogue->unique_reference;
            $notes = $additionalNotes[$uniqueReference] ?? '';

            return [
                'cart_id' => $cartId,
                'catalogue_id' => $item->catalogue_id,
                'id' => $itemCatalogue->id,
                'unique_reference' => $itemCatalogue->unique_reference,
                'type' => $itemCatalogue->type,
                'brand' => $itemCatalogue->brand,
                'name' => $itemCatalogue->name,
                'vendor' => $itemCatalogue->vendor,
                'category' => $itemCatalogue->category,
                'price_inc_gst' => $itemCatalogue->price_inc_gst,
                'processor' => $itemCatalogue->processor,
                'storage' => $itemCatalogue->storage,
                'memory' => $itemCatalogue->memory,
                'form_factor' => $itemCatalogue->form_factor,
                'display' => $itemCatalogue->display,
                'graphics' => $itemCatalogue->graphics,
                'wireless' => $itemCatalogue->wireless,
                'webcam' => $itemCatalogue->webcam,
                'operating_system' => $itemCatalogue->operating_system,
                'warranty' => $itemCatalogue->warranty,
                'battery_life' => $itemCatalogue->battery_life,
                'weight' => $itemCatalogue->weight,
                'stylus' => $itemCatalogue->stylus,
                'other' => $itemCatalogue->other,
                'available_now' => $itemCatalogue->available_now,
                'corporate' => $itemCatalogue->corporate,
                'administration' => $itemCatalogue->administration,
                'curriculum' => $itemCatalogue->curriculum,
                'image' => $itemCatalogue->image,
                'product_number' => $itemCatalogue->product_number,
                'price_expiry' => $itemCatalogue->price_expiry,
                'cover_image' => [
                    'uuid' => $itemImageUUID,
                    'extension' => $itemImageExtension,
                ],
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->catalogue->price_inc_gst,
                'notes' => $notes
            ];
        })->values()->all();
    }


    private function createQuote($userId, $versionId, $quoteContent, $totalPrice, $deliveryInfo)
    {
        $quote = Quote::create([
            'user_id' => $userId,
            'version_id' => $versionId,
            'quote_ref' => '',
            'quote_content' => $quoteContent,
            'total_price_ex_gst' => number_format($totalPrice / 1.1, 2, '.', ''),
            'delivery_info' => json_encode($deliveryInfo),
            'status' => 'ACTIVE',
        ]);

        $quoteId = $quote->id;

        $quoteRef = $this->generateQuoteRef($quoteId);

        $quote->quote_ref = $quoteRef;
        $quote->save();

        return $quote;
    }
  
    private function generateQuoteRef($quoteId)
    {
        $date = date('Ymd'); // Get current date in YYYYMMDD format
        $randomNumber = mt_rand(10000, 99999); // Generate a random 5-digit number

        return "{$date}-{$quoteId}-{$randomNumber}";
    }

    private function removeCheckedOutItems($cart, $vendorCartItems)
    {
        $cart->cartItems()->whereIn('id', $vendorCartItems->pluck('id'))->delete();
    }

    private function updateCartStatus($cart)
    {
        if ($cart->cartItems()->count() == 0) {
            $cart->update(['status' => 'COMPLETED']);
            Cart::create([
                'user_id' => $cart->user_id,
                'version_id' => $cart->version_id,
                'status' => 'ACTIVE',
            ]);
        }
    }

    public function getActiveUserQuotes()
    {
        $user = Auth::user();
        $quotes = Quote::where('user_id', $user->id)
            ->where('version_id', Catalogueversion::getActiveCatalogueId())
            ->where('status', "ACTIVE")
            ->orderBy('created_at', 'desc')
            ->get();
        if ($quotes->isEmpty()) {
            return response()->json(['message' => 'No quotes found'], 410);
        }
        return response()->json(['message' => 'Fetch completed', 'quotes' => $quotes], 201);
    }

    public function getVendorInfo(Request $request, $vendorName)
    {
        $vendor = Vendor::where('vendor_name', $vendorName)->first();
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 410);
        }
        $result = [
            'Name' => $vendor->vendor_name ?? '',
            'Address' => $vendor->address ?? '',
            'ABN' => $vendor->abn ?? '',
            'Order Email' => $vendor->order_email ?? '',
            'Phone' => $vendor->phone ?? '',
            "Contact" => $vendor->contact ?? '',
            'Direct Phone' => $vendor->direct_phone ?? '',
            'Email' => $vendor->email ?? '',
        ];
        return response()->json(['message' => 'Fetch completed', 'vendor' => $result], 201);

    }


}
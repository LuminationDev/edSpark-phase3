<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogueController extends Controller
{
    public function fetchAllTypes()
    {
        $types = Catalogue::distinct('type')->pluck('type');
        return response()->json($types);
    }

    public function fetchAllBrands()
    {
        $brands = Catalogue::distinct('brand')->pluck('brand');
        return response()->json($brands);
    }

    public function fetchAllVendors()
    {
        $vendors = Catalogue::distinct('vendor')->pluck('vendor');
        return response()->json($vendors);
    }

    public function fetchAllCategories()
    {
        $categories = Catalogue::distinct('category')->pluck('category');
        return response()->json($categories);
    }

    // Accept field and value from request and return list of products matches the query
    public function fetchCatalogueByField(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'field' => 'required|in:type,brand,vendor,category',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), null, 400);
        }

        $inputField = $request->input('field');
        $value = $request->input('value');

        $field = $inputField;

        // Use paginate for pagination
        $perPage = $request->input('per_page', 20);
        $queryResult = Catalogue::excludeBundlesAndUpgrades()->where($field, $value)->paginate($perPage);

        if ($queryResult->isEmpty()) {
            return ResponseService::error('No results found.', null, 404);
        }

        return ResponseService::success('Results fetched successfully.', [
            'items' => $queryResult->items(),
            'pagination' => [
                'current_page' => $queryResult->currentPage(),
                'total_pages' => $queryResult->lastPage(),
                'per_page' => $queryResult->perPage(),
                'total_items' => $queryResult->total(),
            ],
        ]);
    }

    // Get Individual products, excluding bundle offers and upgrades
    public function fetchAllProducts(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $products = Catalogue::excludeBundlesAndUpgrades()
            ->paginate($perPage);

        return ResponseService::success('Products fetched successfully.', [
            'items' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total_items' => $products->total(),
            ],
        ]);
    }
    // Get full information regarding a product
    public function fetchSingleProductByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), null, 400);
        }

        // Fetch the product by name
        $product = Catalogue::where('name', $request->input('name'))->first();

        // Check if the product exists
        if (!$product) {
            return ResponseService::error('Product not found.', null, 404);
        }

        // Check for available upgrades
        $upgradeAvailable = Catalogue::where('name', $request->input('name'))
            ->where('type', 'Upgrade')
            ->exists();

        // Check for available bundles
        $bundleAvailable = Catalogue::where('name', 'like', $request->input('name') . '%')
            ->where('type', 'Bundle')
            ->exists();

        // Add fields to the product
        $product->upgrade_available = $upgradeAvailable;
        $product->bundle_available = $bundleAvailable;

        return ResponseService::success('Product fetched successfully.', $product);
    }


    // Get available upgrade for a product
    public function fetchUpgradesSingleProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), null, 400);
        }

        // Query for available upgrades
        $upgrades = Catalogue::where('name', $request->input('name'))
            ->where('type', 'Upgrade')
            ->get();

        return ResponseService::success('Upgrades fetched successfully.', $upgrades);
    }


    // Get available bundle offer for a product
    public function fetchBundlesSingleProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), null, 400);
        }

        // Query for available bundles
        $bundles = Catalogue::where('name', 'like', $request->input('name') . '%')
            ->where('type', 'Bundle')
            ->get();

        return ResponseService::success('Bundles fetched successfully.', $bundles);
    }



}

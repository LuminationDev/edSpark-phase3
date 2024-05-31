<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Catalogueversion;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Outerweb\ImageLibrary\Models\Image;

class CatalogueController extends Controller
{
    public static function catalogueModelToJson($item)
    {
        if ($item->cover_image) {
            $itemImage = Image::where('id', $item->cover_image)->first();
            $itemImageUUID = $itemImage->uuid ?? '';
        } else {
            $itemImageUUID = '';
        }
        return [
            'id' => $item->id,
            'unique_reference' => $item->unique_reference,
            'type' => $item->type,
            'brand' => $item->brand,
            'name' => $item->name,
            'vendor' => $item->vendor,
            'category' => $item->category,
            'price_inc_gst' => $item->price_inc_gst,
            'processor' => $item->processor,
            'storage' => $item->storage,
            'memory' => $item->memory,
            'form_factor' => $item->form_factor,
            'display' => $item->display,
            'graphics' => $item->graphics,
            'wireless' => $item->wireless,
            'webcam' => $item->webcam,
            'operating_system' => $item->operating_system,
            'warranty' => $item->warranty,
            'battery_life' => $item->battery_life,
            'weight' => $item->weight,
            'stylus' => $item->stylus,
            'other' => $item->other,
            'available_now' => $item->available_now,
            'corporate' => $item->corporate,
            'administration' => $item->administration,
            'curriculum' => $item->curriculum,
            'image' => $item->image,
            'product_number' => $item->product_number,
            'price_expiry' => $item->price_expiry,
            'cover_image' => [
                'uuid' => $itemImageUUID,
                'extension' => $itemImage->file_extension ?? '',
            ]
        ];
    }

    public function fetchAllTypes()
    {
        $types = Catalogue::distinct('type')->pluck('type');
        return ResponseService::success('Fetch success', $types);

    }

    public function fetchAllBrands(): \Illuminate\Http\JsonResponse
    {
        $brands = Catalogue::distinct('brand')->pluck('brand');
        return ResponseService::success('Fetch success', $brands);

    }

    public function fetchAllVendors(): \Illuminate\Http\JsonResponse
    {
        $vendors = Catalogue::distinct('vendor')->pluck('vendor');
        return ResponseService::success('Fetch success', $vendors);

    }

    public function fetchAllCategories(): \Illuminate\Http\JsonResponse
    {
        $categories = Catalogue::distinct('category')->pluck('category');
        return ResponseService::success('Fetch success', $categories);
    }

    // Accept field and value from request and return list of products matches the query
    public function fetchCatalogueByField(Request $request): \Illuminate\Http\JsonResponse
    {
        $field = $request->input('field');
        $values = $request->input('value') ?? [];
        $perPage = $request->input('per_page', 20);
        $requestedPage = $request->query('page', 1);
        $additionalFilters = $request->input('additional_filters', []);

        // Start building the base query
        $query = Catalogue::query();
        // filter based on catalogue version id
        $query->where('version_id', Catalogueversion::getActiveCatalogueId());

        // if field and values is not empty, apply filter query
        if (!empty($field) && !empty($values)) {
            $query->whereIn($field, $values);
        }

        // Apply additional filters
        foreach ($additionalFilters as $filterField => $filterValue) {
            if ($filterField === 'price' && is_array($filterValue) && count($filterValue) === 2) {
                $minPrice = intval($filterValue[0]);
                $maxPrice = intval($filterValue[1]);
                $query->whereRaw('CAST(price_inc_gst AS UNSIGNED) BETWEEN ? AND ?', [$minPrice, $maxPrice]);
            } else {
                if ($field !== $filterField && count($filterValue)) {
                    $query->whereIn($filterField, is_array($filterValue) ? $filterValue : [$filterValue]);
                }
            }
        }

        // perform check for item is active or not active
        $query->where('curriculum', 'Yes')->where("available_now", '<>', 'No');

        // Fetch distinct values for available fields
        foreach (['type', 'brand', 'vendor', 'category', 'processor', 'memory', 'storage'] as $otherField) {
            if ($otherField != $field) {
                $availableFields[$otherField] = $query->pluck($otherField)->unique()->values()->all();

            }
        }

        // Paginate the results and handle page overflow
        $paginatedQueryResult = $query->paginate($perPage, ['*'], 'page', $requestedPage);

        // Adjust the page if the requested page is out of bounds
        if ($requestedPage > $paginatedQueryResult->lastPage()) {
            $paginatedQueryResult = $query->paginate($perPage, ['*'], 'page', 1);
        }

        // Transform results to JSON format
        $itemResults = $paginatedQueryResult->map(function ($item) {
            return $this->catalogueModelToJson($item);
        });

        return ResponseService::success('Results fetched successfully.', [
            'items' => $itemResults,
            'available_fields' => $availableFields,
            'pagination' => [
                'current_page' => $paginatedQueryResult->currentPage(),
                'total_pages' => $paginatedQueryResult->lastPage(),
                'per_page' => $paginatedQueryResult->perPage(),
                'total_items' => $paginatedQueryResult->total(),
            ],
        ]);
    }


    public function fetchAllCatalogue(Request $request)
    {
        $perPage = $request->input('per_page', 24);

        $products = Catalogue::paginate($perPage);

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

        return ResponseService::success('Product fetched successfully.', $product);
    }

    public function fetchSingleProductByUniqueReference(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_reference' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), null, 400);
        }

        // Fetch the product by name
        $product = Catalogue::where('unique_reference', $request->input('unique_reference'))->where('version_id', Catalogueversion::getActiveCatalogueId())->first();


        // Check if the product exists
        if (!$product) {
            return ResponseService::error('Product not found.', null, 404);
        }

        $product = $this->catalogueModelToJson($product);


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

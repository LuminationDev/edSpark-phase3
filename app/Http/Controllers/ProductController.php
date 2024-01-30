<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Hardware;
use App\Models\Productbrand;
use App\Models\Software;
use App\Models\Softwaremeta;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Productbrand as Brand;
use App\Models\Productcategory as Category;
use App\Models\Hardware as Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function fetchAllBrands()
    {
        $brands = Brand::all();
        $data = [];

        if ($brands) {
            foreach ($brands as $brand) {
                $result = [
                    'brandName' => $brand->product_brand_name,
                    'brandDescription' => $brand->product_brand_description
                ];
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    public function fetchAllCategories()
    {

        $categories = Category::all();
        $data = [];

        if ($categories) {
            foreach ($categories as $category) {
                $result = [
                    'categoryName' => $category->product_category_name,
                    'categoryDescription' => $category->product_category_description,
                ];
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    private function hardwareModelToJson($hardware, Request $request = NULL)
    {
        $userId = Auth::user()->id;
        $isLikedByUser = $hardware->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $hardware->bookmarks()->where('user_id', $userId)->exists();
        return [
            'id' => $hardware->id,
            'author' => [
                'author_id' => $hardware->owner_id,
                'author_name' => $hardware->owner->full_name ?? NULL,
            ],
            'title' => $hardware->product_name,
            'content' => $hardware->product_content,
            'excerpt' => $hardware->product_excerpt,
            'price' => $hardware->price,
            'cover_image' => $hardware->cover_image ?? NULL,
            'gallery' => $hardware->gallery ?? NULL,
            'SKU' => $hardware->product_SKU,
            'category' => [
                'categoryId' => $hardware->category_id ?? NULL,
                'categoryName' => $hardware->category->product_category_name ?? NULL,
            ],
            'brand' => [
                'brandId' => $hardware->brand_id ?? NULL,
                'brandName' => $hardware->brand->product_brand_name ?? NULL,
            ],
            'modified_at' => $hardware->modified,
            'isLoan' => $hardware->product_isLoan ?? NULL,
            'extra_content' => $hardware->extra_content ?? NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
        ];
    }


    public function fetchAllProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        $hardwares = Product::inRandomOrder()->get();
        $data = [];

        if ($hardwares) {
            foreach ($hardwares as $hardware) {
                $result = $this->hardwareModelToJson($hardware, $request);
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    public function fetchUserProductPosts(Request $request): JsonResponse
    {
        try {
            $userId = Auth::user()->id;

            $hardwares = Hardware::where('owner_id', $userId)  // Filter by partner (author) ID
            ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($hardwares as $hardware) {
                $result = $this->hardwareModelToJson($hardware, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function fetchProductById(Request $request)
    {
        $id = $request->id;
        $hardware = Product::find($id);
        $data = $this->hardwareModelToJson($hardware, $request);

        return ResponseService::success('Success fetch product', $data);
    }

    public function fetchProductByBrand(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'currentId' => 'required|integer|exists:hardwares,id',
        ]);

        // The validation will automatically return a 422 Unprocessable Entity
        // response if the validation fails, so there's no need for additional
        // error handling for that.

        $currentProduct = Product::find($validatedData['currentId']);

        // Get the brand of the current product
        $currentBrandId = $currentProduct->brand_id;

        // Fetch two other products of the same brand excluding the current product
        $relatedProducts = Product::where('brand_id', $currentBrandId)
            ->where('id', '!=', $validatedData['currentId'])
            ->inRandomOrder()
            ->take(2)
            ->get();

        $data = [];
        foreach ($relatedProducts as $product) {
            $result = $this->hardwareModelToJson($product, $request);
            $data[] = $result;
        }

        return response()->json($data);
    }
}

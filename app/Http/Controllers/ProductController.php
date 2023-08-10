<?php

namespace App\Http\Controllers;

use App\Models\Productbrand;
use Illuminate\Http\Request;
use App\Models\Productbrand as Brand;
use App\Models\Productcategory as Category;
use App\Models\Hardware as Product;

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

    private function hardwareModelToJson($hardware,Request $request = NULL)
    {
        $userId = 0;
        $isLikedByUser = false;
        $isBookmarkedByUser = false;

        if (isset($request) && $request->has('usid')) {
            $userId = $request->input('usid');
            $isLikedByUser = $hardware->likes()->where('user_id', $userId)->exists();
            $isBookmarkedByUser = $hardware->bookmarks()->where('user_id', $userId)->exists();

        }
        return [
            'id' => $hardware->id,
            'author' => [
                'author_id' => $hardware->owner_id,
                'author_name' => $hardware->owner->full_name ?? NULL,
            ],
            'product_name' => $hardware->product_name,
            'product_content' => $hardware->product_content,
            'product_excerpt' => $hardware->product_excerpt,
            'price' => $hardware->price,
            'cover_image' => $hardware->cover_image ?? NULL,
            'gallery' => $hardware->gallery ?? NULL,
            'product_SKU' => $hardware->product_SKU,
            'category' => [
                'categoryId' => $hardware->category_id ?? NULL,
                'categoryName' => $hardware->category->product_category_name ?? NULL,
            ],
            'brand' => [
                'brandId' => $hardware->brand_id ?? NULL,
                'brandName' => $hardware->brand->product_brand_name ?? NULL,
            ],
            'product_isLoan' => $hardware->product_isLoan ?? NULL,
            'extra_content' => $hardware->extra_content ?? NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
        ];
    }


    public function fetchAllProducts(Request $request)
    {
        $hardwares = Product::all();
        $data = [];

        if ($hardwares) {
            foreach ($hardwares as $hardware) {
                $result = $this->hardwareModelToJson($hardware, $request);
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    public function fetchProductById(Request $request,$id)
    {
        $hardware = Product::find($id);
        $data = $this->hardwareModelToJson($hardware, $request);

        return response()->json($data);
    }

    public function fetchProductByBrand(Request $request, $brand)
    {
        $hardwareBrand = Productbrand::where('product_brand_name', $brand)->get();
        $hardwareBrandIdInEdspark = $hardwareBrand[0]->id;
        $hardwares = Product::where('brand_id', $hardwareBrandIdInEdspark)->get();
        $data = [];

        if ($hardwares) {
            foreach ($hardwares as $hardware) {
                $result = $this->hardwareModelToJson($hardware,$request);
                $data[] = $result;
            }
        }

        return response()->json($data);
    }
}

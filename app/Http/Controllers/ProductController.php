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

        if($brands) {
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

        if($categories) {
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

    public function fetchAllProducts()
    {
        $products = Product::all();
        $data = [];

        if($products) {
            foreach ($products as $product) {

                $result = [
                    'id' => $product->id,
                    'author'=> [
                        'author_id' => $product->owner_id,
                        'author_name' => ($product->owner_id) ? $product->owner->full_name : NULL
                    ],
                    'product_name' => $product->product_name,
                    'product_content' => $product->product_content,
                    'product_excerpt' => $product->product_excerpt,
                    'price' => $product->price,
                    'cover_image' => ($product->cover_image) ? $product->cover_image : NULL,
                    'gallery' => ($product->gallery) ? $product->gallery : NULL,
                    'product_SKU' => $product->product_SKU,
                    'category' => [
                        'categoryId' => ($product->category_id) ? $product->category_id : NULL,
                        'categoryName' => ($product->category) ? $product->category->product_category_name : NULL,
                    ],
                    'brand' => [
                        'brandId' => ($product->brand_id) ? $product->brand_id : NULL,
                        'brandName' => ($product->brand) ? $product->brand->product_brand_name : NULL,
                    ],
                    'product_isLoan' => ($product->product_isLoan) ? $product->product_isLoan : NULL,
                    'extra_content' => ($product->extra_content) ?: NULL

                ];
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    public function fetchProductById($id)
    {
        $product = Product::find($id);
        $data = [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'author'=> [
                'author_id' => $product->owner_id,
                'author_name' => ($product->owner_id) ? $product->owner->full_name : NULL
            ],
            'product_content' => $product->product_content,
            'product_excerpt' => $product->product_excerpt,
            'price' => $product->price,
            'cover_image' => ($product->cover_image) ?: NULL,
            'gallery' => ($product->gallery) ?: NULL,
            'product_SKU' => $product->product_SKU,
            'category' => [
                'categoryId' => ($product->category_id) ?: NULL,
                'categoryName' => ($product->category) ? $product->category->product_category_name : NULL,
            ],
            'brand' => [
                'brandId' => ($product->brand_id) ? $product->brand_id : NULL,
                'brandName' => ($product->brand) ? $product->brand->product_brand_name : NULL,
            ],
            'product_isLoan' => ($product->product_isLoan) ? $product->product_isLoan : NULL,
            'extra_content' => ($product->extra_content) ?: NULL

        ];

        return response()->json($data);
    }

    public function fetchProductByBrand($brand)
    {
        $productBrand = Productbrand::where('product_brand_name', $brand)->get();
        $productBrandIdInEdspark = $productBrand[0]->id;
        $products = Product::where('brand_id', $productBrandIdInEdspark)->get();
        $data = [];

        if ($products) {
            forEach($products as $product) {
                $result = [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'author'=> [
                        'author_id' => $product->owner_id,
                        'author_name' => ($product->owner_id) ? $product->owner->full_name : NULL
                    ],
                    'product_content' => $product->product_content,
                    'product_excerpt' => $product->product_excerpAt,
                    'price' => $product->price,
                    'cover_image' => ($product->cover_image) ? $product->cover_image : NULL,
                    'gallery' => ($product->gallery) ? $product->gallery : NULL,
                    'product_SKU' => $product->product_SKU,
                    'category' => [
                        'categoryId' => ($product->category_id) ? $product->category_id : NULL,
                        'categoryName' => ($product->category) ? $product->category->product_category_name : NULL,
                    ],
                    'brand' => [
                        'brandId' => ($product->brand_id) ? $product->brand_id : NULL,
                        'brandName' => ($product->brand) ? $product->brand->product_brand_name : NULL,
                    ],
                    'product_isLoan' => ($product->product_isLoan) ? $product->product_isLoan : NULL,
                    'extra_content' => ($product->extra_content) ?: NULL
                ];

                $data[] = $result;
            }
        }

        return response()->json($data);
    }
}

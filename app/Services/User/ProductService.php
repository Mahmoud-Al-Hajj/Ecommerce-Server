<?php

namespace App\Services\User;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Services\Common\Base64ConverterService;
use Illuminate\Http\Request;

class ProductService{



    public static function getAllProducts($request){
        $perPage = $request->per_page; //5 x 3 on frontend
        $gender = $request->product_gender;
        $category = $request->category_id;
        if (!$perPage) {
            $perPage = 1000; // Default value if not provided
        }

        $query = Product::query()
        ->where('visible', true)
        ->select('id', 'name', 'price', 'category_id', 'product_gender')
        ->with(['images' => function ($query) {
            $query->where('is_thumbnail', true)->select('product_id', 'image_url');
        }]);

        if ($gender) {
            $query->where('product_gender', $gender);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        return $query->paginate($perPage);
    }


    public static function getProductById($id){
        return Product::where('visible', true)->with('images', 'sizes')->findOrFail($id);
    }

public static function createProduct($request){
        $product = new Product;
        $product->name = $request["name"] ?? null;
        $product->description = $request["description"] ?? null;
        $product->price = $request["price"] ?? null;
        $product->category_id = $request["category_id"] ?? null;
        $product->product_gender = $request["product_gender"] ?? null;
        $product->quantity = $request["quantity"] ?? null;
        $product->visible = $request["visible"] ?? true;
        $product->save();

        foreach ($request['sizes'] as $sizeData) {
            $productSize = new ProductSize;
            $productSize->product_id = $product->id;
            $productSize->size = $sizeData['size'];
            $productSize->stock = $sizeData['stock'] ?? 0;
            $productSize->save();
        }
        foreach ($request['images'] as $img) {
            $url = Base64ConverterService::base64ToImage($img['image_url']);
            $image = new ProductImage;
            $image->product_id = $product->id;
            $image->image_url = $url;
            $image->is_thumbnail = $img['is_thumbnail'] ?? false;
            $image->save();
        }
        //$urls = Base64ConverterService::convert($request['images']);
        return $product->load('images');
    }

    public static function updateProduct(Request $request, $id){

        $product = Product::findOrFail($id);
        $product->name = $request["name"] ?? $product->name;
        $product->price = $request["price"] ?? $product->price;
        $product->quantity = $request["quantity"] ?? $product->quantity;
        $product->description = $request["description"] ?? $product->description;
        $product->category_id = $request["category_id"] ?? $product->category_id;
        $product->product_gender = $request["product_gender"] ?? $product->product_gender;
        $product->visible = $request["visible"] ?? $product->visible;
        $product->save();

        if (!empty($request['images']) && is_array($request['images'])) {
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($request['images'] as $img) {
                $url = Base64ConverterService::base64ToImage($img['image_url']);
                $image = new ProductImage;
                $image->product_id = $product->id;
                $image->image_url = $url;
                $image->is_thumbnail = $img['is_thumbnail'] ?? false;
                $image->save();
            }
        }
        if (!empty($request['sizes']) && is_array($request['sizes'])) {
            ProductSize::where('product_id', $product->id)->delete();
            foreach ($request['sizes'] as $sizeData) {
                $productSize = new ProductSize;
                $productSize->product_id = $product->id;
                $productSize->size = $sizeData['size'];
                $productSize->stock = $sizeData['stock'] ?? 0;
                $productSize->save();
            }
        }
        return $product->load('images');
    }

    public static function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->visible = false;
        $product->save();
        return ["status" => "success, deleted product"];
    }

}

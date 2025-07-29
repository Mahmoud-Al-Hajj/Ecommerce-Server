<?php

namespace App\Services\User;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Common\Base64ConverterService;
use Illuminate\Http\Request;

class ProductService{

      public static function getAllProducts(){
    return Product::with('images')->get();

    }

    public static function getProductById($id){
        return Product::with('images')->findOrFail($id);
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

        if (!empty($request['images']) && is_array($request['images'])) {
            foreach ($request['images'] as $img) {
            $url = Base64ConverterService::base64ToImage($img['image_url']);
            $image = new ProductImage;
            $image->product_id = $product->id;
            $image->image_url = $url;
            $image->is_thumbnail = $img['is_thumbnail'] ?? false;
            $image->save();
        }
    }
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
        return $product->load('images');
    }

    public static function deleteProduct($id){
        Product::findOrFail($id)->delete();
        return ["status" => "success, deleted product"];
    }


}

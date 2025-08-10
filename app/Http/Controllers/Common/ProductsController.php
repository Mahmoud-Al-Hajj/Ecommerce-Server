<?php

namespace App\Http\Controllers\Common;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\ProductService;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductsController extends Controller{

    function getAllProducts(Request $request){
        $products = ProductService::getAllProducts($request);
        return $this->ResponseJSON($products, 200);
    }

    function getProductById($id){
        $product = ProductService::getProductById($id);
        return $this->ResponseJSON($product, 200);
    }

    function createProduct(CreateProductRequest $request){
        $product = ProductService::createProduct($request);
        return $this->ResponseJSON($product, 200);
    }

    function updateProduct($id, UpdateProductRequest $request){
        $product = ProductService::updateProduct($request, $id);
        return $this->ResponseJSON($product, 200);
    }

    function deleteProduct($id){
        $product = ProductService::deleteProduct($id);
        return $this->ResponseJSON($product, 200);
    }

}

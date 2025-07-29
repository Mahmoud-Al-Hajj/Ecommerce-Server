<?php

namespace App\Http\Common\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\User\ProductService;
use Illuminate\Http\Request;
class ProductsController extends Controller{

    function getAllProducts(){
        $products = ProductService::getAllProducts();
        return $this->ResponseJSON($products, 200);
    }

    function getProductById($id){
        $product = ProductService::getProductById($id);
        return $this->ResponseJSON($product, 200);
    }

    function createProduct(Request $request){
        $product = ProductService::createProduct($request);
        return $this->ResponseJSON($product, 200);
    }

    function updateProduct($id, Request $request){
        $product = ProductService::updateProduct($request, $id);
        return $this->ResponseJSON($product, 200);
    }

    function deleteProduct($id){
        $product = ProductService::deleteProduct($id);
        return $this->ResponseJSON($product, 200);
    }

}

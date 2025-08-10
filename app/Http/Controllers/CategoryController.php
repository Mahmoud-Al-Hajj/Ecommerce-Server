<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller{

    public function GetAllCategories(){
        $categories = Category::all();
        return $this->responseJSON($categories,200);
    }

}

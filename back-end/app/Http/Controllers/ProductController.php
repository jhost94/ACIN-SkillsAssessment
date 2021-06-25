<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function addProduct(Request $request){

        $rules = [
            "name" => "required|min:3",
            "prep_time" => "required",
            "weight" => "required",
            "price" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return response($validator->errors(), 400);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->prep_time = $request->prep_time;
        $product->weigth = $request->weight;
        $product->price = $request->price;

        $product->save();

        return response("Product craeted");
    }
}

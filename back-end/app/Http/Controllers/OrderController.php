<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function placeOrder(Request $request){

        if (Auth::user() == null) {
            return response("Not loged in", 400);
        }

        $rules = [
            "customer_id" => "required",
            "product_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->product_id = $request->product_id;

        $order->save();

        return response("Order placed");
    }

    public function getOrders()
    {
        $user = Auth::user();

        if ($user == null) {
            return response("Not logged in", 400);
        }

        return response(Order::all()
                        ->where("customer_id", "=",
                                    Customer::all()
                                        ->where("user_id", "=", $user->id)[0]->id));
        //return response(Customer::all()->where("user_id", "=", $user->id));
        //return response(Order::all()->where());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Order};
use Illuminate\Http\Request;

class OrderController extends Controller
{
   
    public function getCustomerOrders(Customer $customer)
    {
        return response()->json($customer->orders);
    }

    
    public function getOrderDetails(Order $order)
    {
        return view('orders._order_details', compact('order'));
    }
}


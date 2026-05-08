<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->sortByDesc('created_at');
        return view('admin.order.index', compact('orders'));
    }

    public function show($id){

        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id',$order->id)->get();

        return view('admin.order.show', compact('order','orderItems'));
        
    }

    public function updateStatus($id)
    {
        //fetch order by id
        $order = Order::findOrFail($id);

        //update order status
        if(Auth::user()->role->role_name == 'admin' || Auth::user()->role->role_name == 'cashier' )
        {
            $order->status = 'settlement';
        }else{
              $order->status = 'cooked';
        }
      
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Update status order successfully');
    }

  
}

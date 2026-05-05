<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk sebelum checkout.');
        }
        $tableNumber = Session::get('table_number');
        
        return view('customer.checkout',compact('cart', 'tableNumber'));
    }

    public function store(Request $request)
    {
       $cart = Session::get('cart', []);
       $tableNumber = Session::get('table_number');

     if(empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang masih kosong');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:qris,cash',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')->withErrors($validator);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['quantity'] * $item['price'];

            $itemDetails[] = [
                'id' => $item['id'],
                'price' => (int) ($item['price'] + ($item['price'] * 0.1)),
                'quantity' => $item['quantity'],
                'name' => substr($item['name'], 0, 50),
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'role_id' => 4
        ]);

        $order = Order::create([
            'order_code' => 'ORD-'.$tableNumber.'-'. time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => 0.1 * $totalAmount,
            'grand_total' => $totalAmount + (0.1 * $totalAmount),
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        foreach ($cart as $itemId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'] * $item['quantity'],
                'tax' => 0.1 * $item['price'] * $item['quantity'],
                'total_price' => ($item['price'] * $item['quantity']) + (0.1 * $item['price'] * $item['quantity']),
            ]);
        }

        Session::forget('cart');
         if($request->payment_method == 'cash') {
            return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Pesanan berhasil dibuat');
        } else {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_code,
                        'gross_amount' =>  (int) $order->grand_total,
                ],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'fullname' => $user->fullname ?? 'Guest',
                        'phone' => $user->phone,
                ],
                    'payment_type' => 'qris',
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_code' => $order->order_code,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat pesanan. Silakan coba lagi.'
                ]);
            }
        }
      
    }   

    
    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method == 'qris') {
            $order->status  = 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));

    }
}

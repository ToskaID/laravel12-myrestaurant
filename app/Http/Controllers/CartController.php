<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Item;

class CartController extends Controller
{

    //mengambil card data item  dari session setelah user mengklik tambah keranjang 
    public function cart()
    {
        $cart = Session::get('cart',[]);
        return view('customer.cart', compact('cart'));
    }

    //update quantity item di cart dengan menerima request dari frontend berupa id item dan quantity baru    
    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQty = $request->input('quantity');

        if ($newQty <= 0) {
            return response()->json(['success' => false]);
        }

        $cart = Session::get('cart');
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $newQty;
            Session::put('cart', $cart);
            Session::flash('success', 'Jumlah item berhasil diperbarui');

            return response()->json([ 'success' => true]);
        }

        return response()->json(['success' => false]);
    }


    public function removeCart(Request $request) {
        $itemId = $request->input('id');

        $cart = Session::get('cart');

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);

            Session::flash('success', 'Item berhasil dihapus dari keranjang');

            return response()->json(['success' => true]);
        }
    }

        public function clearCart()
    {
        Session::forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang berhasil dikosongkan');
    }


}

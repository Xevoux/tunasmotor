<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->jumlah += $request->jumlah;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'jumlah' => $request->jumlah,
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->count();

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang', 'cartCount' => $cartCount]);
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $carts = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = 0;
        foreach ($carts as $cart) {
            $harga = $cart->product->harga_diskon ?? $cart->product->harga;
            $total += $harga * $cart->jumlah;
        }

        return view('cart', compact('carts', 'total'));
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart->jumlah = $request->jumlah;
        $cart->save();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return response()->json(['success' => true]);
    }

    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Cart::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }
}

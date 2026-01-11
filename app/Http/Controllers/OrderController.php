<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('layouts.pages.order', compact('orders'));
    }

    /**
     * Display specific order details
     */
    public function show(Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat pesanan.');
        }

        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load('orderItems.product');

        return view('layouts.pages.order_details', compact('order'));
    }

    /**
     * Cancel order (only for pending orders)
     */
    public function cancel(Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        // Restore stock
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('stok', $item->jumlah);
                $item->product->decrement('terjual', $item->jumlah);
            }
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Retry payment for pending order
     */
    public function retryPayment(Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if (!$order->canBePaid()) {
            return back()->with('error', 'Pesanan ini tidak dapat dibayar.');
        }

        return redirect()->route('checkout.payment', $order->id);
    }
}


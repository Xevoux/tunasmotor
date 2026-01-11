<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)
            ->with(['product.category'])
            ->get();

        // Remove cart items with deleted products
        $invalidCarts = $carts->filter(fn($cart) => !$cart->product);
        if ($invalidCarts->count() > 0) {
            Cart::whereIn('id', $invalidCarts->pluck('id'))->delete();
            $carts = $carts->filter(fn($cart) => $cart->product);
        }

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($carts as $cart) {
            if ($cart->product) {
                $harga = $cart->product->harga_diskon ?? $cart->product->harga;
                $subtotal += $harga * $cart->jumlah;
            }
        }

        return view('layouts.pages.checkout', compact('carts', 'subtotal', 'user'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'telepon_penerima' => 'required|string|max:20',
            'alamat_pengiriman' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:500',
            'metode_pembayaran' => 'required|in:midtrans,cod',
        ], [
            'nama_penerima.required' => 'Nama penerima harus diisi',
            'telepon_penerima.required' => 'Nomor telepon harus diisi',
            'alamat_pengiriman.required' => 'Alamat pengiriman harus diisi',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
        ]);

        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        // Remove cart items with deleted products
        $invalidCarts = $carts->filter(fn($cart) => !$cart->product);
        if ($invalidCarts->count() > 0) {
            Cart::whereIn('id', $invalidCarts->pluck('id'))->delete();
            $carts = $carts->filter(fn($cart) => $cart->product);
        }

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }

        // Check stock availability
        foreach ($carts as $cart) {
            if (!$cart->product || $cart->product->stok < $cart->jumlah) {
                $productName = $cart->product->nama ?? 'Produk';
                $availableStock = $cart->product->stok ?? 0;
                return back()->with('error', "Stok {$productName} tidak mencukupi. Tersedia: {$availableStock}");
            }
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $totalHarga = 0;
            foreach ($carts as $cart) {
                $harga = $cart->product->harga_diskon ?? $cart->product->harga;
                $totalHarga += $harga * $cart->jumlah;
            }

            $metodePembayaran = $request->metode_pembayaran;
            $isCod = $metodePembayaran === 'cod';

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'nomor_pesanan' => Order::generateOrderNumber(),
                'total_harga' => $totalHarga,
                'diskon' => 0,
                'total_bayar' => $totalHarga,
                'status' => $isCod ? Order::STATUS_PROCESSING : Order::STATUS_PENDING,
                'metode_pembayaran' => $isCod ? 'COD' : 'midtrans',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'nama_penerima' => $request->nama_penerima,
                'telepon_penerima' => $request->telepon_penerima,
                'catatan' => $request->catatan,
            ]);

            // Create order items
            foreach ($carts as $cart) {
                $harga = $cart->product->harga_diskon ?? $cart->product->harga;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'jumlah' => $cart->jumlah,
                    'harga' => $harga,
                    'subtotal' => $harga * $cart->jumlah,
                ]);

                // Decrease stock
                $cart->product->decrement('stok', $cart->jumlah);
                $cart->product->increment('terjual', $cart->jumlah);
            }

            // If COD, no need for Midtrans token
            if ($isCod) {
                // Clear cart
                Cart::where('user_id', $user->id)->delete();

                DB::commit();

                // Send unpaid invoice email
                try {
                    Mail::to($order->user->email)->send(new InvoiceMail($order, false));
                } catch (\Exception $e) {
                    Log::error('Failed to send unpaid invoice email: ' . $e->getMessage());
                }

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Pesanan COD berhasil dibuat! Siapkan pembayaran saat barang diterima.');
            }

            // Create Midtrans snap token for online payment
            $snapToken = $this->createSnapToken($order);
            
            if (!$snapToken) {
                throw new \Exception('Gagal membuat token pembayaran. Silakan coba lagi.');
            }

            $order->update(['snap_token' => $snapToken]);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show payment page
     */
    public function payment(Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melakukan pembayaran.');
        }

        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if (!$order->canBePaid()) {
            return redirect()->route('orders.show', $order->id)
                ->with('info', 'Pesanan ini sudah dibayar atau tidak dapat dibayar.');
        }

        // Ensure snap token exists
        if (empty($order->snap_token)) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Token pembayaran tidak tersedia. Silakan hubungi admin.');
        }

        $order->load('orderItems.product');

        return view('layouts.pages.payment', [
            'order' => $order,
            'clientKey' => config('services.midtrans.client_key'),
            'snapToken' => $order->snap_token,
        ]);
    }

    /**
     * Create Midtrans Snap Token
     */
    private function createSnapToken(Order $order): ?string
    {
        // Guard: ensure Midtrans configuration is present
        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            Log::error('Midtrans configuration missing. Please set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in .env');
            throw new \Exception('Konfigurasi Midtrans belum diisi. Silakan hubungi admin.');
        }

        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $serverKey;
        
        // Special handling: Some sandbox accounts don't have SB- prefix
        // If key doesn't start with SB-, we might need to use production mode = true
        // even though it's actually sandbox (based on our test that worked)
        $configuredIsProduction = config('services.midtrans.is_production');
        
        if (!$configuredIsProduction && !str_starts_with($serverKey, 'SB-')) {
            // Keys without SB- prefix might need production mode = true
            // even for sandbox environment (based on Midtrans account type)
            \Midtrans\Config::$isProduction = false; // Keep sandbox for now
            Log::info('Using sandbox mode for credentials without SB- prefix');
        } else {
            \Midtrans\Config::$isProduction = $configuredIsProduction;
        }
        
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
        
        // SSL verification - enable in production, disable in local
        $isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => $isProduction,
            CURLOPT_SSL_VERIFYHOST => $isProduction ? 2 : 0,
        ];

        $order->load('orderItems.product', 'user');

        // Build item details
        $items = [];
        foreach ($order->orderItems as $item) {
            // Handle case where product might have been deleted
            $productName = $item->product ? $item->product->nama : 'Produk #' . $item->product_id;
            
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->harga,
                'quantity' => $item->jumlah,
                'name' => substr($productName, 0, 50),
            ];
        }

        // Transaction details
        $params = [
            'transaction_details' => [
                'order_id' => $order->nomor_pesanan,
                'gross_amount' => (int) $order->total_bayar,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->nama_penerima,
                'email' => $order->user->email,
                'phone' => $order->telepon_penerima,
                'shipping_address' => [
                    'first_name' => $order->nama_penerima,
                    'phone' => $order->telepon_penerima,
                    'address' => $order->alamat_pengiriman,
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.finish', $order->id),
            ],
        ];

        try {
            // Suppress PHP warnings/notices due to bug in Midtrans SDK
            // (undefined array key 10023 issue)
            $oldErrorReporting = error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            $snapToken = @\Midtrans\Snap::getSnapToken($params);
            error_reporting($oldErrorReporting);
            
            // If we got a token, return it
            if ($snapToken) {
                Log::info('Snap token created successfully for order ' . $order->id);
                return $snapToken;
            }
            
            return null;
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            
            // Log detailed error for debugging
            Log::error('Midtrans Snap Token Error: ' . $errorMsg, [
                'order_id' => $order->id,
                'error_code' => $e->getCode(),
            ]);
            
            // Check for authentication errors
            if (strpos($errorMsg, '401') !== false || strpos($errorMsg, 'Access denied') !== false) {
                Log::error('Midtrans authentication failed. Please check your credentials in .env file.');
            }
            
            return null;
        }
    }

    /**
     * Handle payment callback from Midtrans
     */
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('nomor_pesanan', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;
        $fraudStatus = $request->fraud_status ?? null;

        // Update payment details
        $order->update([
            'transaction_id' => $request->transaction_id,
            'payment_type' => $paymentType,
            'transaction_status' => $transactionStatus,
            'payment_details' => $request->all(),
        ]);

        // Handle transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $order->update([
                    'status' => Order::STATUS_PAID,
                    'paid_at' => now(),
                ]);

                // Send paid invoice email
                try {
                    Mail::to($order->user->email)->send(new InvoiceMail($order, true));
                } catch (\Exception $e) {
                    Log::error('Failed to send paid invoice email: ' . $e->getMessage());
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->update([
                'status' => Order::STATUS_PAID,
                'paid_at' => now(),
            ]);

            // Send paid invoice email
            try {
                Mail::to($order->user->email)->send(new InvoiceMail($order, true));
            } catch (\Exception $e) {
                Log::error('Failed to send paid invoice email: ' . $e->getMessage());
            }
        // === PERBAIKAN DI SINI: Menambahkan elseif untuk status pending ===
        } elseif ($transactionStatus == 'pending') {
            $order->update(['status' => Order::STATUS_PENDING]);
        // === BATAS PERBAIKAN ===
        } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
            // Restore stock
            $this->restoreStock($order);
        } elseif ($transactionStatus == 'expire') {
            $order->update(['status' => Order::STATUS_EXPIRED]);
            // Restore stock
            $this->restoreStock($order);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Payment finish page - Check transaction status from Midtrans
     */
    public function finish(Request $request, Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat pesanan.');
        }

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Check transaction status from Midtrans
        try {
            // Set Midtrans configuration
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
            
            // SSL verification - enable in production, disable in local
            $isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => $isProduction,
                CURLOPT_SSL_VERIFYHOST => $isProduction ? 2 : 0,
            ];

            // Get transaction status from Midtrans
            $oldErrorReporting = error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            $status = @\Midtrans\Transaction::status($order->nomor_pesanan);
            error_reporting($oldErrorReporting);
            
            if ($status) {
                $transactionStatus = $status->transaction_status;
                $paymentType = $status->payment_type ?? null;
                $transactionId = $status->transaction_id ?? null;
                
                // Update order with transaction details
                $order->update([
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                    'transaction_status' => $transactionStatus,
                ]);
                
                // Update order status based on transaction status
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    // Payment successful
                    $order->update([
                        'status' => Order::STATUS_PAID,
                        'paid_at' => now(),
                    ]);
                    
                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
                } elseif ($transactionStatus == 'pending') {
                    // Payment pending
                    $order->update(['status' => Order::STATUS_PENDING]);
                    
                    return redirect()->route('orders.show', $order->id)
                        ->with('info', 'Menunggu pembayaran. Silakan selesaikan pembayaran Anda.');
                } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                    // Payment failed or expired
                    $order->update(['status' => Order::STATUS_CANCELLED]);
                    
                    // Restore stock
                    $this->restoreStock($order);
                    
                    return redirect()->route('orders.show', $order->id)
                        ->with('error', 'Pembayaran gagal atau dibatalkan.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error checking transaction status: ' . $e->getMessage());
        }

        // Default redirect if status check fails
        return redirect()->route('orders.show', $order->id)
            ->with('info', 'Pesanan Anda sedang diproses.');
    }

    /**
     * Restore stock when order is cancelled/expired
     */
    private function restoreStock(Order $order): void
    {
        foreach ($order->orderItems as $item) {
            // Check if product still exists before restoring stock
            if ($item->product) {
                $item->product->increment('stok', $item->jumlah);
                $item->product->decrement('terjual', $item->jumlah);
            }
        }
    }
}
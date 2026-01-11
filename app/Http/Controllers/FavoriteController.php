<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of user's favorites.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $favorites = Auth::user()->favorites()
            ->with('product.category')
            ->latest()
            ->get();

        // Filter out favorites with deleted products
        $invalidFavorites = $favorites->filter(fn($fav) => !$fav->product);
        if ($invalidFavorites->count() > 0) {
            Favorite::whereIn('id', $invalidFavorites->pluck('id'))->delete();
            $favorites = $favorites->filter(fn($fav) => $fav->product);
        }

        return view('layouts.pages.favorites', compact('favorites'));
    }

    /**
     * Toggle favorite status for a product.
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Produk dihapus dari favorit'
            ]);
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Produk ditambahkan ke favorit'
            ]);
        }
    }

    /**
     * Remove a product from favorites.
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari favorit'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Favorit tidak ditemukan'
        ], 404);
    }

    /**
     * Get count of user's favorites.
     */
    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Auth::user()->favorites()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get list of user's favorite product IDs.
     */
    public function list(Request $request)
    {
        // Only allow AJAX requests to prevent direct access showing JSON
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('home');
        }

        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'favorites' => []
            ]);
        }
        
        $favoriteIds = Auth::user()->favorites()
            ->pluck('product_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'favorites' => $favoriteIds
        ]);
    }
}


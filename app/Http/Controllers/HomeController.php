<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil 6 produk terbaru (diurutkan berdasarkan id terbesar/terbaru ditambahkan)
        $newProducts = Product::with('category')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // Total products untuk categories component
        $totalProducts = Product::count();

        // Ambil kategori dengan jumlah produk
        $categories = Category::withCount('products')->get();

        return view('index', compact('newProducts', 'categories', 'totalProducts'));
    }

    /**
     * Products page with category filter
     */
    public function products(Request $request)
    {
        // Query untuk produk dengan filter dan sort
        $query = Product::with('category');

        // Filter berdasarkan kategori
        $categoryId = $request->get('category');
        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        // Sort berdasarkan pilihan (default: populer/terjual)
        $sortBy = $request->get('sort', 'popular');
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('id', 'desc');
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(harga_diskon, harga) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(harga_diskon, harga) DESC');
                break;
            case 'discount':
                $query->whereNotNull('diskon_persen')
                      ->orderBy('diskon_persen', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('terjual', 'desc');
                break;
        }

        $products = $query->get();

        // Ambil semua kategori
        $categories = Category::withCount('products')->get();

        // Current filters untuk UI state
        $currentCategory = $categoryId;
        $currentSort = $sortBy;

        return view('layouts.pages.products', compact('products', 'categories', 'currentCategory', 'currentSort'));
    }

    /**
     * API endpoint untuk filter/sort produk (AJAX)
     */
    public function filterProducts(Request $request)
    {
        $query = Product::with('category');

        // Filter berdasarkan kategori
        $categoryId = $request->get('category');
        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        // Sort berdasarkan pilihan
        $sortBy = $request->get('sort', 'popular');
        switch ($sortBy) {
            case 'newest':
                // Urutkan berdasarkan id terbesar (produk terakhir ditambahkan)
                $query->orderBy('id', 'desc');
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(harga_diskon, harga) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(harga_diskon, harga) DESC');
                break;
            case 'discount':
                $query->whereNotNull('diskon_persen')
                      ->orderBy('diskon_persen', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('terjual', 'desc');
                break;
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }
}

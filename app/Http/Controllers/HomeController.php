<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil produk terbaru
        $newProducts = Product::where('is_new', true)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        // Ambil semua produk dengan rating terbaik
        $products = Product::with('category')
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get();

        // Ambil semua kategori
        $categories = Category::all();

        return view('home', compact('newProducts', 'products', 'categories'));
    }
}

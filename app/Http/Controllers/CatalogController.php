<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $products = Product::with(['category', 'variants'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($qc) use ($search) {
                            $qc->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();
        // Fallback to a dummy number if not set in .env
        $whatsappNumber = env('WHATSAPP_NUMBER', '+584140000000');

        return view('catalog.index', compact('products', 'categories', 'search', 'categoryId', 'whatsappNumber'));
    }
}

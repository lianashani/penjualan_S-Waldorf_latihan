<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'activeVariants', 'activeImages', 'approvedRatings'])
            ->active()
            ->inStock();

        if ($request->filled('kategori')) {
            $query->where('id_kategori', (int) $request->input('kategori'));
        }
        if ($request->filled('min_price')) {
            $min = (int) $request->input('min_price');
            $query->whereRaw('COALESCE(harga_min, harga) >= ?', [$min]);
        }
        if ($request->filled('max_price')) {
            $max = (int) $request->input('max_price');
            $query->whereRaw('COALESCE(harga_min, harga) <= ?', [$max]);
        }

        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(harga_min, harga) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(harga_min, harga) DESC');
                break;
            case 'rating':
                $query->orderBy('rating_average', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('rating_average', 'desc');
                break;
        }
        $query->orderBy('nama_produk');

        $produks = $query->paginate(9);

        $kategoris = Kategori::withCount(['produks' => function($q){
            $q->active()->inStock();
        }])->orderBy('nama_kategori')->get();

        return view('member.catalog.index', compact('produks', 'kategoris'));
    }

    public function show($id)
    {
        $produk = Produk::with(['kategori', 'images' => function($query) {
            $query->where('is_active', true)->orderBy('is_primary', 'desc')->orderBy('urutan');
        }])->findOrFail($id);

        return view('member.catalog.show', compact('produk'));
    }
}

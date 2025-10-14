<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['kategori', 'variants', 'images', 'ratings'])
            ->orderBy('nama_produk')
            ->get();
        return view('home.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('home.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'variants' => 'required_if:has_variants,true|array',
            'variants.*.ukuran' => 'required_with:variants|string|max:50',
            'variants.*.warna' => 'required_with:variants|string|max:50',
            'variants.*.kode_warna' => 'nullable|string|max:7',
            'variants.*.stok' => 'required_with:variants|integer|min:0',
            'variants.*.harga' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only([
            'nama_produk', 'id_kategori', 'deskripsi', 'harga',
            'is_featured', 'is_active', 'has_variants'
        ]);

        // Generate slug
        $data['slug'] = Str::slug($request->nama_produk);

        // Create product
        $produk = Produk::create($data);

        // Handle variants
        if ($request->has_variants && $request->variants) {
            foreach ($request->variants as $variantData) {
                $hargaVariant = isset($variantData['harga']) && $variantData['harga'] !== null
                    ? max(0, (float)$variantData['harga'])
                    : (float)$produk->harga;

                ProductVariant::create([
                    'id_produk' => $produk->id_produk,
                    'ukuran' => $variantData['ukuran'],
                    'warna' => $variantData['warna'],
                    'kode_warna' => $variantData['kode_warna'] ?? null,
                    'stok' => $variantData['stok'],
                    'harga' => $hargaVariant,
                    'is_active' => true
                ]);
            }
        } else {
            // Single product without variants
            $request->validate([
                'ukuran' => 'required|string|max:100',
                'warna' => 'required|string|max:100',
                'stok' => 'required|integer|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $produk->update([
                'ukuran' => $request->ukuran,
                'warna' => $request->warna,
                'stok' => $request->stok
            ]);

            if ($request->hasFile('gambar')) {
                $produk->update([
                    'gambar' => $request->file('gambar')->store('produk', 'public')
                ]);
            }
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                // Validate image
                if ($image->isValid()) {
                    $imagePath = $image->store('produk', 'public');
                    ProductImage::create([
                        'id_produk' => $produk->id_produk,
                        'gambar' => $imagePath,
                        'alt_text' => $produk->nama_produk . ' - Gambar ' . ($index + 1),
                        'urutan' => $index,
                        'is_primary' => $index === 0,
                        'is_active' => true
                    ]);
                }
            }
        }

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('home.produk.show', compact('produk'));
    }

    public function edit($id)
    {
        $produk = Produk::with(['variants', 'images'])->findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('home.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            // Debug: Log the request data
            \Log::info('Update Product Request:', [
                'has_variants' => $request->has_variants,
                'variants' => $request->variants,
                'all_data' => $request->all()
            ]);

            // Sanitize variants before validation: drop empty rows, clamp stok/harga, reindex
            if ($request->has('variants') && is_array($request->variants)) {
                $cleanVariants = [];
                foreach ($request->variants as $idx => $variantData) {
                    if (!is_array($variantData)) continue;
                    $ukuran = isset($variantData['ukuran']) ? trim((string)$variantData['ukuran']) : '';
                    $warna  = isset($variantData['warna']) ? trim((string)$variantData['warna']) : '';
                    // Skip variants with both ukuran and warna empty
                    if ($ukuran === '' && $warna === '') {
                        continue;
                    }
                    $stok = isset($variantData['stok']) ? (int)$variantData['stok'] : 0;
                    $harga = array_key_exists('harga', $variantData) && $variantData['harga'] !== null && $variantData['harga'] !== ''
                        ? max(0, (float)$variantData['harga'])
                        : null; // null means use base price later

                    $clean = [
                        'ukuran' => $ukuran,
                        'warna' => $warna,
                        'kode_warna' => $variantData['kode_warna'] ?? null,
                        'stok' => max(0, $stok),
                        'harga' => $harga,
                    ];
                    if (isset($variantData['id_variant']) && $variantData['id_variant'] !== '') {
                        $clean['id_variant'] = $variantData['id_variant'];
                    }
                    $cleanVariants[] = $clean;
                }
                // Merge back sanitized variants
                $request->merge(['variants' => $cleanVariants]);
            }

            // If product is set to have variants, ensure at least one valid variant remains
            if ((string)$request->input('has_variants') === '1') {
                $variantsArr = $request->input('variants', []);
                if (empty($variantsArr) || count($variantsArr) < 1) {
                    return back()
                        ->withInput()
                        ->with('error', 'Produk dengan varian harus memiliki minimal 1 varian yang valid.');
                }
            }

            $request->validate([
                'nama_produk' => 'required|string|max:100',
                'id_kategori' => 'required|exists:kategoris,id_kategori',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'is_featured' => 'nullable|in:1',
                'is_active' => 'nullable|in:1',
                'has_variants' => 'required|in:0,1',
                'variants' => 'required_if:has_variants,1|array|min:1',
                'variants.*.ukuran' => 'required_with:variants|string|max:50',
                'variants.*.warna' => 'required_with:variants|string|max:50',
                'variants.*.kode_warna' => 'nullable|string|max:7',
                'variants.*.stok' => 'required_with:variants|integer|min:0',
                'variants.*.harga' => 'nullable|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'id_kategori' => $request->id_kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'has_variants' => $request->has_variants
        ];

        // Update product
        $produk->update($data);

        // Update slug if nama_produk changed
        if ($produk->wasChanged('nama_produk')) {
            $produk->update(['slug' => Str::slug($request->nama_produk)]);
        }

        // Handle variants
        if ($request->has_variants == '1' && $request->variants) {
            // Get existing variant IDs from request
            $existingVariantIds = collect($request->variants)->pluck('id_variant')->filter()->toArray();

            // Delete variants that are not in the request
            if (!empty($existingVariantIds)) {
                $produk->variants()->whereNotIn('id_variant', $existingVariantIds)->delete();
            } else {
                // If no existing variants in request, delete all
                $produk->variants()->delete();
            }

            // Process each variant
            foreach ($request->variants as $index => $variantData) {
                \Log::info("Processing variant {$index}:", $variantData);

                if (isset($variantData['id_variant']) && !empty($variantData['id_variant'])) {
                    // Update existing variant
                    $variant = ProductVariant::where('id_variant', $variantData['id_variant'])
                        ->where('id_produk', $produk->id_produk)
                        ->first();

                    if ($variant) {
                        \Log::info("Updating existing variant {$variant->id_variant}");
                        $hargaVariant = isset($variantData['harga']) && $variantData['harga'] !== null
                            ? max(0, (float)$variantData['harga'])
                            : (float)$produk->harga;
                        $variant->update([
                            'ukuran' => $variantData['ukuran'],
                            'warna' => $variantData['warna'],
                            'kode_warna' => $variantData['kode_warna'] ?? null,
                            'stok' => $variantData['stok'],
                            'harga' => $hargaVariant,
                            'is_active' => true
                        ]);
                    } else {
                        \Log::warning("Variant not found: {$variantData['id_variant']}");
                    }
                } else {
                    // Create new variant
                    \Log::info("Creating new variant");
                    $hargaVariant = isset($variantData['harga']) && $variantData['harga'] !== null
                        ? max(0, (float)$variantData['harga'])
                        : (float)$produk->harga;
                    ProductVariant::create([
                        'id_produk' => $produk->id_produk,
                        'ukuran' => $variantData['ukuran'],
                        'warna' => $variantData['warna'],
                        'kode_warna' => $variantData['kode_warna'] ?? null,
                        'stok' => $variantData['stok'],
                        'harga' => $hargaVariant,
                        'is_active' => true
                    ]);
                }
            }

            // Update product price range and total stock for variants
            $variants = $produk->variants()->where('is_active', true)->get();
            if ($variants->isNotEmpty()) {
                $hargaMin = $variants->min('harga');
                $hargaMax = $variants->max('harga');
                $totalStok = $variants->sum('stok');

                $produk->update([
                    'harga_min' => $hargaMin,
                    'harga_max' => $hargaMax,
                    'total_stok' => $totalStok
                ]);
            }
        } else {
            // Single product without variants - delete all variants first
            $produk->variants()->delete();

            $request->validate([
                'ukuran' => 'required|string|max:100',
                'warna' => 'required|string|max:100',
                'stok' => 'required|integer|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $produk->update([
                'ukuran' => $request->ukuran,
                'warna' => $request->warna,
                'stok' => $request->stok,
                'harga_min' => null,
                'harga_max' => null,
                'total_stok' => null
            ]);

            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($produk->gambar && \Storage::disk('public')->exists($produk->gambar)) {
                    \Storage::disk('public')->delete($produk->gambar);
                }
                $produk->update([
                    'gambar' => $request->file('gambar')->store('produk', 'public')
                ]);
            }
        }

        // Handle image deletion
        if ($request->delete_images) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    // Delete physical file
                    if ($image->gambar && \Storage::disk('public')->exists($image->gambar)) {
                        \Storage::disk('public')->delete($image->gambar);
                    }
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                if ($image->isValid()) {
                    $imagePath = $image->store('produk', 'public');
                    ProductImage::create([
                        'id_produk' => $produk->id_produk,
                        'gambar' => $imagePath,
                        'alt_text' => $produk->nama_produk . ' - Gambar ' . ($index + 1),
                        'urutan' => $produk->images()->count() + $index,
                        'is_primary' => $produk->images()->count() === 0 && $index === 0,
                        'is_active' => true
                    ]);
                }
            }
        }

            return redirect()->route('produk.index')
                ->with('success', 'Produk berhasil diupdate!');

        } catch (\Exception $e) {
            \Log::error('Update Product Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengupdate produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Delete image if exists
        if ($produk->gambar && \Storage::disk('public')->exists($produk->gambar)) {
            \Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function generateBarcode($id)
    {
        $produk = Produk::findOrFail($id);
        if (empty($produk->barcode)) {
            $produk->barcode = 'PRD' . str_pad($produk->id_produk, 6, '0', STR_PAD_LEFT);
            $produk->qr_code = $produk->barcode;
            $produk->save();
        }

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($produk->barcode, $generator::TYPE_CODE_128);

        return response($barcode)
            ->header('Content-Type', 'image/png');
    }

    public function generateQRCode($id)
    {
        $produk = Produk::findOrFail($id);
        if (empty($produk->barcode)) {
            $produk->barcode = 'PRD' . str_pad($produk->id_produk, 6, '0', STR_PAD_LEFT);
            $produk->qr_code = $produk->barcode;
            $produk->save();
        }

        // Use BaconQrCode directly
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);
        $qrCode = $writer->writeString($produk->barcode);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    public function printBarcode($id)
    {
        $produk = Produk::findOrFail($id);
        return view('home.produk.print-barcode', compact('produk'));
    }

    public function downloadBarcode($id)
    {
        $produk = Produk::findOrFail($id);
        if (empty($produk->barcode)) {
            $produk->barcode = 'PRD' . str_pad($produk->id_produk, 6, '0', STR_PAD_LEFT);
            $produk->qr_code = $produk->barcode;
            $produk->save();
        }

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($produk->barcode, $generator::TYPE_CODE_128);

        $filename = 'barcode-' . $produk->barcode . '.png';

        return response($barcode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadQRCode($id)
    {
        $produk = Produk::findOrFail($id);
        if (empty($produk->barcode)) {
            $produk->barcode = 'PRD' . str_pad($produk->id_produk, 6, '0', STR_PAD_LEFT);
            $produk->qr_code = $produk->barcode;
            $produk->save();
        }

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);
        $qrCode = $writer->writeString($produk->barcode);

        $filename = 'qrcode-' . $produk->barcode . '.svg';

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function updateStok(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'action' => 'required|in:add,subtract,set',
            'jumlah' => 'required|integer|min:1'
        ]);

        $jumlah = $request->jumlah;
        $oldStok = $produk->stok;

        switch ($request->action) {
            case 'add':
                $produk->stok += $jumlah;
                $message = "Stok berhasil ditambah {$jumlah} unit. Stok sekarang: {$produk->stok}";
                break;
            case 'subtract':
                if ($produk->stok < $jumlah) {
                    return back()->with('error', 'Stok tidak mencukupi untuk dikurangi!');
                }
                $produk->stok -= $jumlah;
                $message = "Stok berhasil dikurangi {$jumlah} unit. Stok sekarang: {$produk->stok}";
                break;
            case 'set':
                $produk->stok = $jumlah;
                $message = "Stok berhasil diset menjadi {$jumlah} unit";
                break;
        }

        $produk->save();

        return back()->with('success', $message);
    }

    // New methods for elegant catalog
    public function catalog(Request $request)
    {
        $query = Produk::with(['kategori', 'activeVariants', 'activeImages', 'approvedRatings'])
            ->active()
            ->inStock();

        // Category filter
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->integer('kategori'));
        }

        // Price range filter using COALESCE(harga_min, harga)
        if ($request->filled('min_price')) {
            $min = (int) $request->input('min_price');
            $query->whereRaw('COALESCE(harga_min, harga) >= ?', [$min]);
        }
        if ($request->filled('max_price')) {
            $max = (int) $request->input('max_price');
            $query->whereRaw('COALESCE(harga_min, harga) <= ?', [$max]);
        }

        // Rating minimum
        if ($request->filled('rating_min')) {
            $query->where('rating_average', '>=', (int) $request->input('rating_min'));
        }

        // Sorting
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

        $kategoris = Kategori::withCount(['produks' => function($q) {
            $q->active()->inStock();
        }])->orderBy('nama_kategori')->get();

        return view('home.katalog.elegant', compact('produks', 'kategoris'));
    }

    // Member-specific catalog using the same data but rendering member view
    public function memberCatalog(Request $request)
    {
        $query = Produk::with(['kategori', 'activeVariants', 'activeImages', 'approvedRatings'])
            ->active()
            ->inStock();

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->integer('kategori'));
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

        $kategoris = Kategori::withCount(['produks' => function($q) {
            $q->active()->inStock();
        }])->orderBy('nama_kategori')->get();

        return view('member.katalog.elegant', compact('produks', 'kategoris'));
    }

    public function memberCatalogDetail($slug)
    {
        $produk = Produk::with([
            'kategori',
            'activeVariants',
            'activeImages' => function($query) {
                $query->orderBy('urutan')->orderBy('id_image');
            },
            'approvedRatings.user'
        ])->where('slug', $slug)->firstOrFail();

        $sizes = $produk->activeVariants->pluck('ukuran')->unique()->sort();
        $colors = $produk->activeVariants->pluck('warna')->unique()->sort();

        $relatedProducts = Produk::with(['activeImages', 'approvedRatings'])
            ->where('id_kategori', $produk->id_kategori)
            ->where('id_produk', '!=', $produk->id_produk)
            ->active()
            ->inStock()
            ->limit(4)
            ->get();

        // Reuse the same detail template for now (it already hides kasir-only actions)
        return view('home.katalog.elegant-detail', compact('produk', 'sizes', 'colors', 'relatedProducts'));
    }
    public function catalogDetail($slug)
    {
        $produk = Produk::with([
            'kategori',
            'activeVariants',
            'activeImages' => function($query) {
                $query->orderBy('urutan')->orderBy('id_image');
            },
            'approvedRatings.user'
        ])->where('slug', $slug)->firstOrFail();

        // Get available sizes and colors
        $sizes = $produk->activeVariants->pluck('ukuran')->unique()->sort();
        $colors = $produk->activeVariants->pluck('warna')->unique()->sort();

        // Related products
        $relatedProducts = Produk::with(['activeImages', 'approvedRatings'])
            ->where('id_kategori', $produk->id_kategori)
            ->where('id_produk', '!=', $produk->id_produk)
            ->active()
            ->inStock()
            ->limit(4)
            ->get();

        return view('home.katalog.elegant-detail', compact('produk', 'sizes', 'colors', 'relatedProducts'));
    }

    public function getVariantImages(Request $request)
    {
        $produkId = $request->produk_id;
        $ukuran = $request->ukuran;
        $warna = $request->warna;

        $variant = ProductVariant::where('id_produk', $produkId)
            ->where('ukuran', $ukuran)
            ->where('warna', $warna)
            ->where('is_active', true)
            ->first();

        if ($variant) {
            $images = ProductImage::where('id_variant', $variant->id_variant)
                ->where('is_active', true)
                ->ordered()
                ->get();

            if ($images->isEmpty()) {
                // Fallback to product images
                $images = ProductImage::where('id_produk', $produkId)
                    ->where('is_active', true)
                    ->ordered()
                    ->get();
            }

            return response()->json([
                'success' => true,
                'images' => $images->map(function($image) {
                    return [
                        'id' => $image->id_image,
                        'url' => $image->image_url,
                        'thumbnail' => $image->thumbnail_url,
                        'alt' => $image->alt_text
                    ];
                }),
                'variant' => [
                    'id' => $variant->id_variant,
                    'sku' => $variant->sku,
                    'stok' => $variant->stok,
                    'harga' => $variant->formatted_price,
                    'stock_status' => $variant->stock_status,
                    'stock_status_text' => $variant->stock_status_text
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Varian tidak ditemukan']);
    }

    public function addRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
            'nama_pengguna' => 'required_if:id_user,null|string|max:100',
            'email_pengguna' => 'required_if:id_user,null|email|max:100'
        ]);

        $produk = Produk::findOrFail($id);

        // Check if user already rated this product
        $existingRating = ProductRating::where('id_produk', $id)
            ->where(function($query) use ($request) {
                if ($request->id_user) {
                    $query->where('id_user', $request->id_user);
                } else {
                    $query->where('email_pengguna', $request->email_pengguna);
                }
            })
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Anda sudah memberikan rating untuk produk ini!');
        }

        ProductRating::create([
            'id_produk' => $id,
            'id_user' => $request->id_user ?? null,
            'nama_pengguna' => $request->nama_pengguna ?? null,
            'email_pengguna' => $request->email_pengguna ?? null,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'is_approved' => false, // Require admin approval
            'is_verified_purchase' => false
        ]);

        return back()->with('success', 'Rating berhasil dikirim! Menunggu persetujuan admin.');
    }
}

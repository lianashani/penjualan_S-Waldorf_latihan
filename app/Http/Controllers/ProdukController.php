<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->orderBy('nama_produk')->get();
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
            'ukuran' => 'required|string|max:100',
            'warna' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

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
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('home.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'ukuran' => 'required|string|max:100',
            'warna' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar && \Storage::disk('public')->exists($produk->gambar)) {
                \Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate!');
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
}

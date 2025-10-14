<?php

namespace App\Http\Controllers;

use App\Models\ProductRating;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function index()
    {
        $ratings = ProductRating::with(['produk', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.ratings.index', compact('ratings'));
    }

    public function approve($id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->update(['is_approved' => true]);

        return back()->with('success', 'Rating berhasil disetujui!');
    }

    public function reject($id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->update(['is_approved' => false]);

        return back()->with('success', 'Rating berhasil ditolak!');
    }

    public function destroy($id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->delete();

        return back()->with('success', 'Rating berhasil dihapus!');
    }

    public function show($id)
    {
        $rating = ProductRating::with(['produk.kategori', 'user'])->findOrFail($id);

        return response()->json([
            'id_rating' => $rating->id_rating,
            'produk' => [
                'nama_produk' => $rating->produk->nama_produk,
                'kategori' => $rating->produk->kategori->nama_kategori
            ],
            'display_name' => $rating->display_name,
            'email_pengguna' => $rating->email_pengguna,
            'user' => $rating->user ? ['email' => $rating->user->email] : null,
            'rating' => $rating->rating,
            'stars' => $rating->stars,
            'rating_text' => $rating->rating_text,
            'komentar' => $rating->komentar,
            'is_approved' => $rating->is_approved,
            'is_verified_purchase' => $rating->is_verified_purchase,
            'created_at' => $rating->created_at->format('d M Y H:i')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ratings' => 'required|array',
            'ratings.*' => 'exists:product_ratings,id_rating'
        ]);

        $ratings = ProductRating::whereIn('id_rating', $request->ratings);

        switch ($request->action) {
            case 'approve':
                $ratings->update(['is_approved' => true]);
                $message = 'Rating berhasil disetujui!';
                break;
            case 'reject':
                $ratings->update(['is_approved' => false]);
                $message = 'Rating berhasil ditolak!';
                break;
            case 'delete':
                $ratings->delete();
                $message = 'Rating berhasil dihapus!';
                break;
        }

        return back()->with('success', $message);
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category; // <--- WAJIB TAMBAHIN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookBackendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $books = Book::with('peminjamans') // <--- TAMBAHKAN INI
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('pages.backend.book.index', compact('books'));
    }

    public function create()
    {
        // AMBIL SEMUA KATEGORI UNTUK DROPDOWN
        $categories = Category::all();
        return view('pages.backend.book.create', compact('categories'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        // AMBIL SEMUA KATEGORI UNTUK DROPDOWN
        $categories = Category::all();
        return view('pages.backend.book.edit', compact('book', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required',
                'category_id' => 'required', // Pastikan category_id tervalidasi
                'author' => 'required',
                'year' => 'required|numeric',
                'stock' => 'required|numeric|min:0', // <--- PROTEKSI BACKEND (MIN 0)
                'description' => 'required',
                'image_cropped' => 'required'
            ]);

            // Logic otomatis status
            $validated['status'] = $request->stock > 0 ? 'avaiable' : 'not avaiable';

            // PROSES BASE64 KE IMAGE
            if ($request->image_cropped) {
                $imgData = $request->image_cropped;
                $image_parts = explode(";base64,", $imgData);
                $image_base64 = base64_decode($image_parts[1]);

                $fileName = 'books/' . Str::random(10) . '_' . time() . '.jpg';
                Storage::disk('public')->put($fileName, $image_base64);

                $validated['image'] = $fileName;
            }

            Book::create($validated);
            return redirect()->route('book-admin.index')->with('success', 'Buku berhasil ditambah!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'author' => 'required',
            'year' => 'required|numeric',
            'stock' => 'required|numeric|min:0', // <--- PROTEKSI BACKEND (MIN 0)
            'description' => 'required',
            'image_cropped' => 'nullable'
        ]);

        $validated['status'] = $request->stock > 0 ? 'avaiable' : 'not avaiable';

        if ($request->image_cropped) {
            // Hapus gambar lama jika ada dan bukan dari assets
            if ($book->image && !str_starts_with($book->image, 'assets')) {
                Storage::disk('public')->delete($book->image);
            }

            $imgData = $request->image_cropped;
            $image_parts = explode(";base64,", $imgData);
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'books/' . Str::random(10) . '_' . time() . '.jpg';
            Storage::disk('public')->put($fileName, $image_base64);

            $validated['image'] = $fileName;
        }

        $book->update($validated);
        return redirect()->route('book-admin.index')->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy($id)
    {
        // 1. Cari buku beserta relasi peminjamannya
        $book = Book::with('peminjamans')->findOrFail($id);

        // 2. Cek apakah ada transaksi yang statusnya BUKAN 'returned' atau 'rejected'
        // Kita anggap 'rejected' juga tuntas karena buku tidak jadi keluar
        $isBeingBorrowed = $book->peminjamans
            ->whereNotIn('status', ['returned', 'rejected'])
            ->count() > 0;

        if ($isBeingBorrowed) {
            // Jika masih ada transaksi menggantung, gagalkan hapus dan kirim error
            return redirect()->back()->with('error', 'Ada transaksi yang belum tuntas! Selesaikan atau batalkan peminjaman terlebih dahulu.');
        }

        // 3. Jika aman, hapus gambar (kecuali assets)
        if ($book->image && !str_starts_with($book->image, 'assets')) {
            Storage::disk('public')->delete($book->image);
        }

        // 4. Hapus data buku
        $book->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus dari perpustakaan!');
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookBackendController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap inputan pencarian
        $search = $request->search;

        // Query buku dengan fitur Search, Urutan ASC (Berdasarkan ID/Judul), dan Pagination 7
        $books = Book::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('author', 'like', '%' . $search . '%');
        })
            ->orderBy('id', 'asc') // Mengurutkan dari yang paling pertama ditambahkan (ASC)
            ->paginate(5)
            ->withQueryString(); // Biar pas pindah halaman, inputan search nggak ilang

        return view('pages.backend.book.index', compact('books'));
    }

    public function create()
    {
        return view('pages.backend.book.create');
    }


    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('pages.backend.book.edit', compact('book'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required',
                'author' => 'required',
                'year' => 'required|numeric',
                'stock' => 'required|numeric',
                'description' => 'required',
                // Kita gak validasi 'image' sebagai file lagi karena isinya teks base64
                'image_cropped' => 'required'
            ]);

            // Logic otomatis status (Gue tetep pake typo 'avaiable' sesuai DB lo ya wkwk)
            $validated['status'] = $request->stock > 0 ? 'avaiable' : 'not avaiable';

            // PROSES BASE64 KE IMAGE
            if ($request->image_cropped) {
                $imgData = $request->image_cropped;

                // Pecah string base64
                // Format: data:image/jpeg;base64,/9j/4AAQSkZJRg...
                $image_parts = explode(";base64,", $imgData);
                $image_base64 = base64_decode($image_parts[1]);

                // Buat nama file unik
                $fileName = 'books/' . Str::random(10) . '_' . time() . '.jpg';

                // Simpan ke storage public
                Storage::disk('public')->put($fileName, $image_base64);

                // Masukin path-nya ke array validated buat masuk DB
                $validated['image'] = $fileName;
            }

            Book::create($validated);
            return redirect()->route('book-admin.index')->with('success', 'Buku berhasil ditambah!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required',
            'image_cropped' => 'nullable'
        ]);

        $validated['status'] = $request->stock > 0 ? 'avaiable' : 'not avaiable';

        if ($request->image_cropped) {
            // PERBAIKAN: Jangan hapus gambar kalau dia dari folder 'assets'
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
        $book = Book::findOrFail($id);

        // PERBAIKAN: Jangan hapus gambar kalau dia dari folder 'assets'
        if ($book->image && !str_starts_with($book->image, 'assets')) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();
        return redirect()->back()->with('success', 'Buku dihapus!');
    }
}

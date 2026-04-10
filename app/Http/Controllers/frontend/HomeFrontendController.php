<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeFrontendController extends Controller
{
    public function borrow(Book $book)
    {
        $book->decrement('stock'); // stok berkurang 1

        if ($book->stock <= 0) {
            $book->update(['status' => 'not-avaiable']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // PERBAIKAN 1: Gunakan latest()->get() agar buku yang baru ditambah otomatis berada di urutan pertama
        $books = Book::latest()->get();

        // 2. Filter buku yang stoknya > 0 untuk tab "Available"
        $availableBooks = $books->where('stock', '>', 0);

        // 3. Ambil data buku yang SEDANG dipinjam oleh user (My Borrowed)
        $myBorrowedBooks = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approve', 'verifikasi'])
            ->with('book')
            ->get()
            ->pluck('book')
            ->filter();

        return view('pages.frontend.home.index', compact('books', 'availableBooks', 'myBorrowedBooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

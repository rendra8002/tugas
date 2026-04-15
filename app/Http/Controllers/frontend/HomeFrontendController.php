<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Peminjaman;
use Illuminate\Http\Request; // <-- PASTIKAN INI ADA
use Illuminate\Support\Facades\Auth;

class HomeFrontendController extends Controller
{
    public function searchBooks(Request $request)
    {
        try {
            $query = $request->get('q');

            // Jangan cari kalau kosong atau kurang dari 2 huruf
            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }

            $books = Book::where('title', 'LIKE', "%{$query}%")
                ->where('stock', '>', 0)
                ->take(5)
                ->get();

            // Format data
            $formattedBooks = $books->map(function ($book) {
                $imgUrl = asset('assets/img/no-cover.png');

                if (!empty($book->image)) {
                    if (\Illuminate\Support\Str::startsWith($book->image, ['http://', 'https://'])) {
                        $imgUrl = $book->image;
                    } elseif (\Illuminate\Support\Str::startsWith($book->image, 'assets/')) {
                        $imgUrl = asset($book->image);
                    } else {
                        $imgUrl = asset('storage/' . $book->image);
                    }
                }

                return [
                    'title' => $book->title,
                    'author' => $book->author,
                    'image' => $imgUrl,
                    'url' => route('book.show', $book->id)
                ];
            });

            // Pastikan dikirim sebagai Response JSON
            return response()->json($formattedBooks);
        } catch (\Exception $e) {
            return response()->json([
                'pesan_error' => $e->getMessage(),
                'baris' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function borrow(Book $book)
    {
        $book->decrement('stock');

        if ($book->stock <= 0) {
            $book->update(['status' => 'not-avaiable']);
        }
    }

    public function index()
    {
        $books = Book::latest()->get();
        $availableBooks = $books->where('stock', '>', 0);

        $popularBooks = Book::withCount('peminjamans')
            ->orderBy('peminjamans_count', 'desc')
            ->take(12)
            ->get();

        $myBorrowedBooks = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approve', 'verifikasi'])
            ->with('book')
            ->get()
            ->pluck('book')
            ->filter();

        $recommendedBooks = Book::where('stock', '>', 0)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $lastCheckedOut = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['approve', 'returned', 'verifikasi'])
            ->latest()
            ->take(2)
            ->get();

        $categories = Category::has('books')->get();

        $borrowedCategoryIds = $myBorrowedBooks->pluck('category_id')->unique();
        $borrowedCategories = Category::whereIn('id', $borrowedCategoryIds)->get();

        return view('pages.frontend.home.index', compact(
            'books',
            'availableBooks',
            'myBorrowedBooks',
            'recommendedBooks',
            'lastCheckedOut',
            'popularBooks',
            'categories',
            'borrowedCategories'
        ));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}

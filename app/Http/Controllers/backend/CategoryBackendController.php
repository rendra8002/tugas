<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBackendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->withCount('books') // Biar bisa nampilin jumlah buku per kategori
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.backend.category.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.backend.category.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('category-admin.index')->with('success', 'Kategori berhasil ditambah!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.backend.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('category-admin.index')->with('success', 'Kategori diupdate!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Kategori dihapus!');
    }
}

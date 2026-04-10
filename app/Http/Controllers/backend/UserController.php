<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $currentUser = Auth::user();
        $query = User::query();

        if ($currentUser->role === 'petugas') {
            $query->where('role', 'anggota');
        }

        $users = $query->when($search, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('pages.backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('pages.backend.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $data = $request->except(['cropped_image']);
        $data['password'] = Hash::make($request->password);

        if ($request->filled('cropped_image')) {
            $base64Image = $request->cropped_image;
            $image_parts = explode(";base64,", $base64Image);
            $image_base64 = base64_decode($image_parts[1]);

            // Bikin nama file
            $fileName = 'user-' . Str::random(10) . '-' . time() . '.png';
            // Gabungkan dengan nama folder 'users/'
            $filePath = 'users/' . $fileName;

            // Simpan ke storage public
            Storage::disk('public')->put($filePath, $image_base64);

            // SIMPAN FULL PATH ke database (users/namafile.png)
            $data['image'] = $filePath;
        }

        User::create($data);
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.backend.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        $data = $request->except(['cropped_image']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->filled('cropped_image')) {
            // PERBAIKAN LOGIKA HAPUS: Cek kalau gambar ada, DAN bukan gambar seeder
            if ($user->image && !str_starts_with($user->image, 'assets')) {
                // Hapus langsung karena path di DB sudah benar (users/namafile.png)
                Storage::disk('public')->delete($user->image);
            }

            $base64Image = $request->cropped_image;
            $image_parts = explode(";base64,", $base64Image);
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = 'user-' . Str::random(10) . '-' . time() . '.png';
            $filePath = 'users/' . $fileName; // Path lengkap beserta folder

            Storage::disk('public')->put($filePath, $image_base64);

            // SIMPAN FULL PATH ke database
            $data['image'] = $filePath;
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // PERBAIKAN LOGIKA HAPUS SAAT DESTROY
        if ($user->image && !str_starts_with($user->image, 'assets')) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}

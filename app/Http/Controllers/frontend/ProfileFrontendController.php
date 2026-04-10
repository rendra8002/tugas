<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileFrontendController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (url()->previous() !== request()->url()) {
            session(['url_sebelum_profil_fe' => url()->previous()]);
        }

        return view('pages.frontend.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image_cropped' => 'nullable|string',
            'password' => 'nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Proses Gambar Hasil Crop (Base64)
        if ($request->filled('image_cropped')) {
            // Hapus foto lama
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $imageData = $request->image_cropped;
            $imageInfo = explode(";base64,", $imageData);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);
            $image = str_replace(' ', '+', $imageInfo[1]);

            $imageName = 'users/' . time() . '.' . $imgExt;

            Storage::disk('public')->put($imageName, base64_decode($image));
            $user->image = $imageName;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $redirectUrl = session()->pull('url_sebelum_profil_fe', route('index'));
        return redirect($redirectUrl)->with('success', 'Profil kamu berhasil diperbarui!');
    }
}

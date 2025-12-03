<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenulisController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('penulis.articles.index');
    }

    // =========================================================
    // 1. Manajemen Artikel Penulis
    // =========================================================

    public function articleIndex(Request $request)
    {
        $query = Article::where('writer_id', Auth::id());

        $articles = $query->latest()->paginate(10);

        return view('penulis.articles.index', compact('articles'));
    }

    // =========================================================
    // 2. Pengaturan Profil
    // =========================================================

    public function profileEdit()
    {
        $user = Auth::user();
        return view('penulis.profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}

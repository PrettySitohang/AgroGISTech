<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Tambahkan import ini jika belum ada

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
        $query = Article::where('author_id', Auth::id());

        $articles = $query->latest()->paginate(10);

        return view('penulis.articles.index', compact('articles'));
    }

    public function articleCreate()
    {
        return view('penulis.articles.create');
    }

    public function articleStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['status'] = 'draft';
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);

        $article = Article::create($validated);

        // Handle cover image if uploaded
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'article_' . $article->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('articles', $file, $filename);
            $article->update(['cover_image' => '/storage/articles/' . $filename]);
        }

        return redirect()->route('penulis.articles.index')->with('success', 'Artikel berhasil disimpan sebagai draf.');
    }

    public function articleEdit(Article $article)
    {
        if ($article->author_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke artikel ini.');
        }
        return view('penulis.articles.edit', compact('article'));
    }

    public function articleUpdate(Request $request, Article $article)
    {
        if ($article->author_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke artikel ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $article->update($validated);

        // Handle cover image replacement for penulis
        if ($request->hasFile('cover_image')) {
            if ($article->cover_image) {
                $oldPath = str_replace('/storage/', 'public/', $article->cover_image);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            $file = $request->file('cover_image');
            $filename = 'article_' . $article->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('articles', $file, $filename);
            $article->update(['cover_image' => '/storage/articles/' . $filename]);
        }

        return redirect()->route('penulis.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function articleSubmit(Article $article)
    {
        if ($article->author_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke artikel ini.');
        }

        if ($article->status !== 'draft') {
            return back()->with('error', 'Hanya draf yang dapat diajukan untuk review.');
        }

        // Mark the draft as submitted for review (still 'draft' status)
        $article->update(['submitted_for_review' => true]);
        LogService::record('article.submit', 'article', $article->id, ['submitted' => true]);

        return back()->with('success', 'Artikel berhasil diajukan untuk di-review oleh editor (masuk antrian).');
    }

    public function submitForReview(Article $article)
    {
        return $this->articleSubmit($article);
    }

    public function articleDelete(Article $article)
    {
        if ($article->author_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke artikel ini.');
        }

        $article->delete();

        return redirect()->route('penulis.articles.index')->with('success', 'Artikel berhasil dihapus.');
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

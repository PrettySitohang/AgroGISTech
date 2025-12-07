<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $articleCount = Article::count();
        $userCount = User::where('role', 'penulis')->count();
        $logCount = Log::count();

        $editorIds = User::where('role', 'editor')->pluck('id');
        $penulisIds = User::where('role', 'penulis')->pluck('id');

        $editorLogCount = Log::whereIn('actor_id', $editorIds)->count();
        $penulisLogCount = Log::whereIn('actor_id', $penulisIds)->count();

        return view('admin.dashboard', compact(
            'articleCount',
            'userCount',
            'logCount',
            'editorLogCount',
            'penulisLogCount'
        ));
    }

    public function userIndex(Request $request)
    {
        $query = User::latest();
        $query->where('role', '!=', 'super_admin');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($role = $request->get('role')) {
            if ($role !== 'all') {
                $query->where('role', $role);
            }
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,editor,penulis',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        LogService::record('user.create', 'user', $user->id, ['role' => $user->role]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan: ' . $user->name);
    }

    public function articleIndex()
        {
            $articles = Article::with('author', 'editor', 'category', 'tags')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

            $totalArticles = Article::count();
            $publishedCount = Article::where('status', 'published')->count();
            $draftCount = Article::where('status', 'draft')->count();
            $reviewCount = Article::where('status', 'review')->count();

            return view('admin.articles.index', compact(
                'articles',
                'totalArticles',
                'publishedCount',
                'draftCount',
                'reviewCount'
            ));
        }


        public function articleDelete(Article $article)
        {
            $article->delete();
            LogService::record('article.force_delete', 'article', $article->id, ['title' => $article->title]);
            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel "' . $article->title . '" dihapus paksa.');
        }

    public function categoryIndex()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.masters.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories|max:255']);
        $category = Category::create($request->all());
        return back()->with('success', 'Kategori ' . $category->name . ' berhasil dibuat.');
    }

    public function categoryEdit(Category $category)
{
    return redirect()->route('admin.categories.index')->with([
        'category_to_edit' => $category->category_id,
        'info' => "Kategori '{$category->name}' dapat diedit langsung di tabel."
    ]);
}

public function categoryUpdate(Request $request, Category $category)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->category_id . ',category_id',
    ]);

    $category->update($validatedData);

    return response()->json([
        'success' => true,
        'message' => "Kategori '{$category->name}' berhasil diperbarui!",
        'category' => $category
    ]);
}


    public function categoryDelete(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function tagIndex()
    {
        $tags = Tag::latest()->paginate(10);
        return view('admin.masters.tags.index', compact('tags'));
    }

    public function tagStore(Request $request)
    {
        $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $tag = Tag::create($validatedData);
            $request->validate(['name' => 'required|unique:tags|max:255']);
            $tag = Tag::create($request->all());
            return back()->with('success', 'Tag ' . $tag->name . ' berhasil dibuat.');
    }

    public function tagEdit(Tag $tag)
    {
        // PENTING: Jika rute ini diakses secara langsung (misalnya dari history browser),
        // kita akan mengarahkan pengguna kembali ke halaman index.
        return redirect()->route('admin.tags.index')->with([
            'tag_to_edit' => $tag->tag_id,
            'info' => 'Fitur edit dilakukan langsung di tabel.'
        ]);
        // Catatan: Jika Anda tidak ingin redirect, Anda harus mengembalikan view edit.
        // Karena route ini ada, ia HARUS didefinisikan di controller.
    }

    public function tagUpdate(Request $request, Tag $tag)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->tag_id . ',tag_id',
        ]);

        // Mutator di Model Tag akan mengurus pembuatan slug baru.
        $tag->update($validatedData);

        // 2. Kirim respons JSON karena ini adalah permintaan AJAX
        return response()->json([
            'success' => true,
            'message' => "Tag '{$tag->name}' berhasil diperbarui.",
            'tag' => $tag
        ]);
    }

    public function tagDelete(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag berhasil dihapus.');
    }

    public function logs()
    {
        $logs = Log::with('actor')->latest()->paginate(20);
        return view('admin.logs', compact('logs'));
    }

    public function deleteLog(Log $log)
    {
        $log->delete();
        return back()->with('success', 'Log dihapus.');
    }

    // Site settings: logo and site name
    public function settingsIndex()
    {
        $siteName = Setting::get('site_name', config('app.name', 'AgroGISTech'));
        $logoPath = Setting::get('logo');

        return view('admin.settings.index', compact('siteName', 'logoPath'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048'
        ]);

        Setting::set('site_name', $validated['site_name']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('settings', 'public');

            // Remove previous logo if exists
            $old = Setting::get('logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            Setting::set('logo', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan situs berhasil disimpan.');
    }
}

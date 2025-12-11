<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;



class EditorController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('editor.reviews.index');
    }

    public function tagEdit(Tag $tag)
{
    return view('editor.masters.tags.edit', compact('tag'));
}

public function categoryStore(Request $request)
{
    $request->validate([
    'name' => 'required|string|max:255|unique:categories,name',
    ]);

    Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
    ]);

    return redirect()->route('editor.categories.index')
                     ->with('success', 'Kategori berhasil ditambahkan.');
}


    public function reviewIndex(Request $request)
    {
        // Review queue: show drafts that have been submitted for review and not yet claimed by any editor
        $query = Article::where('status', 'draft')
                ->where('submitted_for_review', true)
                ->whereNull('editor_id')
                ->with('author');

        if ($search = $request->get('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $articles = $query->latest()->paginate(15);

        return view('editor.reviews.index', compact('articles'));
    }

    // app/Http/Controllers/EditorController.php

public function claimArticle(Article $article)
    {
        if ($article->editor_id) {
            return back()->with('error', 'Artikel sudah diklaim oleh editor lain.');
        }

        $article->editor_id = Auth::id();
        $article->status = 'review'; // Status berubah dari draft ke review
        $article->submitted_for_review = false; // clear submitted flag
        $article->save();

        LogService::record('article.claimed', 'article', $article->id, ['editor' => Auth::user()->name]);

        // UBAH: Redirect ke Daftar Artikel (editor.articles.index) setelah sukses klaim
        return redirect()->route('editor.articles.index')
            ->with('success', 'Anda berhasil mengklaim artikel ini. Silakan lanjutkan penyuntingan di Daftar Artikel.');
    }
    public function articleIndex()
    {
        // Show REVIEW and PUBLISHED articles (global) so management reflects DB
        $articles = Article::with('author', 'editor', 'category', 'tags')
            ->whereIn('status', ['review', 'published'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Counts should reflect database state for each status
        $totalArticles = Article::whereIn('status', ['review', 'published'])->count();
        $publishedCount = Article::where('status', 'published')->count();
        $reviewCount = Article::where('status', 'review')->count();
        $draftCount = Article::where('status', 'draft')->count();

        return view('editor.articles.index', compact(
            'articles',
            'totalArticles',
            'publishedCount',
            'draftCount',
            'reviewCount'
        ));
    }


    public function articleCreate()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('editor.articles.create', compact('categories', 'tags'));
    }

    public function articleStore(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:articles,title',
            'content' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'array',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'author_id' => Auth::id(),
            'status' => 'review',
        ]);

        if ($request->filled('tags')) {
            $article->tags()->sync($request->tags);
        }

        LogService::record('article.created', 'article', $article->id, ['author' => Auth::user()->name]);

        return redirect()->route('editor.articles.edit', $article)
            ->with('success', 'Artikel baru berhasil dibuat. Silakan lanjutkan penyuntingan.');
    }

    public function articleEdit(Article $article)
    {
        if (
            $article->status === 'published' ||
            $article->status === 'archived' ||
            ($article->editor_id && $article->editor_id !== Auth::id())
        ) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }

        $revisions = ArticleRevision::where('article_id', $article->id)->latest()->get();
        $categories = Category::all();
        $tags = Tag::all();

        return view('editor.articles.edit', compact('article', 'revisions', 'categories', 'tags'));
    }

    public function categoryEdit(Category $category)
    {
        return view('editor.masters.categories.edit', compact('category'));
    }

    /**
     * Perbarui kategori yang sudah ada di database.
     */
    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'max:100',

                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // LogService::record('category.updated', 'category', $category->id, ['name' => $request->name, 'editor' => Auth::user()->name]);

        return redirect()->route('editor.categories.index')
                         ->with('success', "Kategori '{$request->name}' berhasil diperbarui.");
    }

    /**
     * Hapus kategori dari database.
     */
    public function categoryDestroy(Category $category)
    {
        $categoryName = $category->name;

        // OPSIONAL: Logika pengecekan apakah ada artikel yang masih menggunakan kategori ini
        if ($category->articles()->exists()) {
             return back()->with('error', "Kategori '{$categoryName}' tidak dapat dihapus karena masih digunakan oleh beberapa artikel.");
        }

        $category->delete();

        // LogService::record('category.deleted', 'category', null, ['name' => $categoryName, 'editor' => Auth::user()->name]);

        return redirect()->route('editor.categories.index')
                         ->with('success', "Kategori '{$categoryName}' berhasil dihapus.");
    }

    public function articleUpdate(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'review_notes' => 'required',
            'status' => 'required|in:draft,review,published,archived',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
        ]);

        ArticleRevision::create([
            'article_id' => $article->id,
            'editor_id' => Auth::id(),
            'title_before' => $article->title,
            'content_before' => $article->content,
            'notes' => $request->review_notes,
        ]);

        $updateData = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'editor_id' => Auth::id(),
            'published_at' => ($request->status === 'published' ? now() : $article->published_at),
        ];

        $article->update($updateData);

        if ($request->filled('tags')) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('editor.reviews.index')
            ->with('success', 'Artikel diperbarui dan berstatus: ' . $article->status);
    }

    public function articlePublish(Article $article)
    {

        $article->update([
            'status' => 'published',
            'published_at' => now(),
            'editor_id' => Auth::id(), // Mencatat siapa yang menerbitkan
        ]);

        LogService::record('article.published', 'article', $article->id, ['editor' => Auth::user()->name]);

        return back()->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function categoryIndex()
    {
        $categories = Category::latest()->paginate(10);
        return view('editor.masters.categories.index', compact('categories'));
    }

    public function tagIndex()
    {
        // Only show tags created by the current editor
        $tags = Tag::where('created_by', Auth::id())->latest()->paginate(10);
        return view('editor.masters.tags.index', compact('tags'));
    }


    public function readProfile(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('editor.profile.edit');
        }

        return view('editor.users.profile', compact('user'));
    }

    public function tagStore(Request $request) // <-- TAMBAHKAN
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'created_by' => Auth::id(), // Associate tag with current editor
        ]);

        return redirect()->route('editor.tags.index')
                         ->with('success', 'Tag berhasil ditambahkan.');
    }

    public function tagUpdate(Request $request, Tag $tag) // <-- TAMBAHKAN
    {
        // Verify that the tag belongs to the current editor
        if ($tag->created_by !== Auth::id()) {
            return redirect()->route('editor.tags.index')
                            ->with('error', 'Anda tidak memiliki izin untuk mengubah tag ini.');
        }

        $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Karena AJAX inline, ini mengembalikan redirect (standar form),
        // tetapi di front-end (JS) Anda harus mengantisipasi JSON response.
        return redirect()->route('editor.tags.index')
                         ->with('success', "Tag '{$request->name}' berhasil diperbarui.");
    }

    // Perhatikan nama method harus sesuai dengan nama di Route::delete
    public function tagDestroy(Tag $tag) // <-- TAMBAHKAN (Jika menggunakan route 'tagDestroy')
    {
        $tagName = $tag->name;

        // Logika pengecekan apakah ada artikel yang masih menggunakan tag ini
        if ($tag->articles()->exists()) {
             return back()->with('error', "Tag '{$tagName}' tidak dapat dihapus karena masih digunakan oleh beberapa artikel.");
        }

        $tag->delete();

        // LogService::record('tag.deleted', 'tag', null, ['name' => $tagName, 'editor' => Auth::user()->name]);

        return redirect()->route('editor.tags.index')
                         ->with('success', "Tag '{$tagName}' berhasil dihapus.");
    }

    public function profileEdit()
    {
        $user = Auth::user();
        return view('editor.profile.edit', ['user' => $user]);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($validated['password'])]);
        }

        return redirect()->route('editor.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}

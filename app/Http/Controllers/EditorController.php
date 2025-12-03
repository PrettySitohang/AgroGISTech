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

class EditorController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('editor.reviews.index');
    }

    public function reviewIndex(Request $request)
    {
        $query = Article::where('status', 'pending')
                        ->orWhere('editor_id', Auth::id());

        if ($search = $request->get('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $articles = $query->with('writer')->latest()->paginate(15);

        return view('editor.reviews.index', compact('articles'));
    }

    public function claimArticle(Article $article)
    {
        if ($article->editor_id) {
            return back()->with('error', 'Artikel sudah diklaim oleh editor lain.');
        }

        $article->editor_id = Auth::id();
        $article->status = 'in_review';
        $article->save();

        LogService::record('article.claimed', 'article', $article->id, ['editor' => Auth::user()->name]);

        return redirect()->route('editor.articles.edit', $article)
                         ->with('success', 'Anda berhasil mengklaim artikel ini. Silakan mulai mengedit.');
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

    public function articleUpdate(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'review_notes' => 'required',
            'status' => 'required|in:pending,in_review,published,archived',
            'categories' => 'array',
            'tags' => 'array',
        ]);

        ArticleRevision::create([
            'article_id' => $article->id,
            'editor_id' => Auth::id(),
            'title_before' => $article->title,
            'content_before' => $article->content,
            'notes' => $request->review_notes,
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'editor_id' => Auth::id(),
            'published_at' => ($request->status === 'published' ? now() : $article->published_at),
        ]);

        $article->categories()->sync($request->categories);
        $article->tags()->sync($request->tags);

        return redirect()->route('editor.reviews.index')
                         ->with('success', 'Artikel diperbarui dan berstatus: ' . $article->status);
    }

    public function categoryIndex()
    {
        $categories = Category::latest()->paginate(10);
        return view('editor.masters.categories.index', compact('categories'));
    }

    public function readProfile(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('editor.profile.edit');
        }

        return view('editor.users.profile', compact('user'));
    }
}

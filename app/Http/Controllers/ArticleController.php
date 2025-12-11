<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $articles = Article::with(['author', 'category', 'tags'])
            ->where('status', 'published')

            // SEARCH - Case insensitive dengan LIKE
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            })

            // FILTER CATEGORY
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })

            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        return view('public.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);

        $article->load(['author', 'editor', 'category', 'tags']);

        // Get related articles by category or tags
        $relatedArticles = Article::with(['author', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->where(function ($query) use ($article) {
                // Related by category
                if ($article->category_id) {
                    $query->where('category_id', $article->category_id);
                }
                // Related by tags
                $tagIds = $article->tags->pluck('id')->toArray();
                if (count($tagIds) > 0) {
                    $query->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tag_id', $tagIds);
                    });
                }
            })
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return view('public.show', compact('article', 'relatedArticles'));
    }
}

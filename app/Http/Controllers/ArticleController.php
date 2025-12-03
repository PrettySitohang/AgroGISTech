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

        $articles = Article::with(['author'])
            ->where('status', 'published')

            // SEARCH
            ->when($search, function ($query) use ($search) {
                try {
                    $query->whereFullText(['title', 'content'], $search);
                } catch (\Exception $e) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%")
                          ->orWhere('content', 'like', "%$search%");
                    });
                }
            })

            // FILTER CATEGORY
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })

            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        return view('public.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);

        $article->load('author');

        return view('public.show', compact('article'));
    }
}

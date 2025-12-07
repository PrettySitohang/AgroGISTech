<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function teknologi(Request $request)
    {
        $category = Category::where('slug', 'teknologi')->orWhere('name', 'Teknologi')->first();
        $query = Article::with('author', 'category')
            ->where('status', 'published');

        if ($category) {
            $query->where('category_id', $category->category_id ?? $category->id);
        }

        $articles = $query->latest()->paginate(10);
        return view('pages.teknologi', compact('articles', 'category'));
    }

    public function riset(Request $request)
    {
        $category = Category::where('slug', 'riset')->orWhere('name', 'Riset & Data')->first();
        $query = Article::with('author', 'category')
            ->where('status', 'published');

        if ($category) {
            $query->where('category_id', $category->category_id ?? $category->id);
        }

        $articles = $query->latest()->paginate(10);
        return view('pages.riset', compact('articles', 'category'));
    }

    public function berita(Request $request)
    {
        $articles = Article::with('author', 'category')
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('pages.berita', compact('articles'));
    }

    public function tentang()
    {
        return view('pages.tentang');
    }
}

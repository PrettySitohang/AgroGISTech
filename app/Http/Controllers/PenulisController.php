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
        $contentSource = $request->input('content_source', 'manual');

        $rules = [
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:4096',
        ];

        if ($contentSource === 'manual') {
            $rules['content'] = 'required|string';
        } else {
            $rules['document'] = 'required|file|mimes:pdf,docx,doc,txt|max:10240';
        }

        $validated = $request->validate($rules);

        $content = '';

        if ($contentSource === 'upload' && $request->hasFile('document')) {
            $content = $this->extractTextFromDocument($request->file('document'));
        } else {
            $content = $request->input('content', '');
        }

        $validated['author_id'] = Auth::id();
        $validated['status'] = 'draft';
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['content'] = $content;

        $article = Article::create($validated);

        // Handle cover image if uploaded
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'article_' . $article->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/articles', $filename);
            $article->update(['cover_image' => '/storage/articles/' . $filename]);
        }

        return redirect()->route('penulis.articles.index')->with('success', 'Artikel berhasil disimpan sebagai draf.');
    }

    private function extractTextFromDocument($file)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'pdf') {
            return $this->extractTextFromPDF($file);
        } elseif (in_array($extension, ['docx', 'doc'])) {
            return $this->extractTextFromWord($file);
        } elseif ($extension === 'txt') {
            return file_get_contents($file->getRealPath());
        }

        return '';
    }

    private function extractTextFromWord($file)
    {
        try {
            // Membutuhkan library seperti 'phpoffice/phpword'
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getRealPath());
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $elementText = $this->getElementText($element);
                    if ($elementText) {
                        $text .= $elementText . "\n";
                    }
                }
            }

            return trim($text);
        } catch (\Exception $e) {
            return "Dokumen berhasil diupload. Silakan edit konten di sini.\n\n" . $file->getClientOriginalName();
        }
    }

    private function getElementText($element)
    {
        if (method_exists($element, 'getText')) {
            return $element->getText();
        }

        if (method_exists($element, 'getElements')) {
            $text = '';
            foreach ($element->getElements() as $subElement) {
                $text .= $this->getElementText($subElement);
            }
            return $text;
        }

        return '';
    }

    private function extractTextFromPDF($file)
    {
        // For PDF, we'll use a simple approach - store the file and extract using command line if available
        // For now, return a placeholder message
        return "Document uploaded. Anda dapat mengedit konten di sini.\n\n" . $file->getClientOriginalName();
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
            $file->storeAs('public/articles', $filename);
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

        // Update status from 'draft' to 'review' for editor review queue
        $article->update(['status' => 'review']);
        LogService::record('article.submit', 'article', $article->id, ['status' => 'review']);

        return back()->with('success', 'Artikel berhasil diajukan untuk di-review oleh editor.');
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

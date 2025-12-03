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
use Illuminate\Support\Facades\Auth; // <-- PENTING: Import Facade Auth

class AdminController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('admin.users.index');
    }

    public function userIndex(Request $request)
    {
        $query = User::latest();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,editor,penulis',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        LogService::record('user.create', 'user', $user->id, ['role' => $user->role]);
        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,editor,penulis',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $oldRole = $user->role;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Baris ini (update) sudah benar dan berfungsi.
        $user->update($data);

        if ($oldRole !== $user->role) {
            LogService::record('user.role_change', 'user', $user->id, ['old_role' => $oldRole, 'new_role' => $user->role]);
        } else {
            LogService::record('user.update', 'user', $user->id, ['role' => $user->role]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ' . $user->name . ' diperbarui.');
    }

    public function userDelete(User $user)
    {
        // PERBAIKAN: Mengganti auth()->user()->id dengan Auth::id() untuk kompatibilitas dan kejelasan
        if (Auth::id() === $user->id) {
             return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        LogService::record('user.delete', 'user', $user->id, ['name' => $user->name]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }

    public function articleIndex()
    {
        $articles = Article::with('writer', 'editor')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function articleDelete(Article $article)
    {
        $article->delete();
        LogService::record('article.force_delete', 'article', $article->id, ['title' => $article->title]);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel "' . $article->title . '" dihapus paksa.');
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

    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|unique:categories,name,' . $category->id . '|max:255']);
        $category->update($request->all());
        return back()->with('success', 'Kategori diperbarui.');
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
        $request->validate(['name' => 'required|unique:tags|max:255']);
        $tag = Tag::create($request->all());
        return back()->with('success', 'Tag ' . $tag->name . ' berhasil dibuat.');
    }

    public function tagUpdate(Request $request, Tag $tag)
    {
        $request->validate(['name' => 'required|unique:tags,name,' . $tag->id . '|max:255']);
        $tag->update($request->all());
        return back()->with('success', 'Tag diperbarui.');
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
        return back()->with('success','Log dihapus.');
    }
}

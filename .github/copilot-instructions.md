# AgroGISTech AI Coding Instructions

## 🎯 Project Overview

**AgroGISTech** is a Laravel 12 content management system for agricultural technology articles, featuring a multi-role workflow: **Penulis (Writers)** → **Editor (Reviewers)** → **Admin (Moderators)**. The system implements a strict article lifecycle with role-based access control and audit logging.

## 🏗️ Architecture & Data Flow

### Core Role-Based Workflow

```
PENULIS (Writer)          EDITOR (Reviewer)         ADMIN (Moderator)
├─ Create draft           ├─ Claim articles         ├─ User management
├─ Edit own drafts        ├─ Review & edit          ├─ Publish articles
├─ Submit for review      ├─ Approve/reject         ├─ Delete articles
└─ View own articles      └─ Publish articles       └─ Master data (categories, tags)
```

### Article Lifecycle States

- **draft** → Article created by writer, not visible to public, no editor assigned
- **review** → Writer submitted for review; editor claims it, assigning themselves
- **published** → Editor approved; visible publicly with byline

Key: Article moves from writer → editor → published. Each transition is logged via `LogService`.

### Data Relationships

- **User** → multiple articles as author (`articles()`) or editor (`editedArticles()`)
- **Article** → belongs to author (User), editor (User), category, tags (M2M via `article_tag`)
- **ArticleRevision** → tracks article edit history (references Article)
- **Log** → audit trail; records all significant actions with actor, action type, entity, and metadata

## 🔄 Critical Route Patterns

Routes are **always named** (e.g., `penulis.articles.create`) and use **middleware `role`** for authorization.

### Penulis Routes (`/penulis/*` - `role:penulis`)
- `GET /penulis/articles` → `penulis.articles.index` (view all own articles)
- `GET /penulis/articles/create` → `penulis.articles.create` (form)
- `POST /penulis/articles` → `penulis.articles.store` (create)
- `GET /penulis/articles/{id}/edit` → `penulis.articles.edit` (form; draft only)
- `PUT /penulis/articles/{id}` → `penulis.articles.update` (auto-generate slug)
- `POST /penulis/articles/{id}/submit` → `penulis.articles.submit` (draft → review)
- `DELETE /penulis/articles/{id}` → `penulis.articles.delete` (soft delete; draft only)

### Editor Routes (`/editor/*` - `role:editor`)
- `GET /editor/reviews` → `editor.reviews.index` (unclaimed draft articles)
- `POST /editor/reviews/{id}/claim` → `editor.reviews.claim` (draft → review; assign editor)
- `GET /editor/articles` → `editor.articles.index` (own review + published)
- `GET /editor/articles/{id}/edit` → `editor.articles.edit` (review status only)
- `PUT /editor/articles/{id}` → `editor.articles.update` (edit in review state)
- `PUT /editor/articles/{id}/publish` → `editor.articles.publish` (review → published)
- `DELETE /editor/articles/{id}` → `editor.articles.destroy` (soft delete)

### Admin Routes (`/admin/*` - `role:super_admin`)
- Full CRUD for users, articles (force delete), categories, tags, settings, logs
- Master data management (categories, tags created here, but editors can also create)

### Public Routes (`/` - no auth)
- `GET /` → `public.index` (paginated published articles, searchable by title/content)
- `GET /article/{slug}` → `public.show` (published article + related articles by category/tags)

## 🛠️ Developer Workflow

### Setup (First Time)
```bash
composer install
cp .env.example .env
php artisan key:generate
# Update .env: DB_*, GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Seeded test accounts:
- Admin: `admin@agrogistech.test` / `password123` (role: `super_admin`)
- Editor: `editor@agrogistech.test` / `password123` (role: `editor`)
- Writer: `penulis@agrogistech.test` / `password123` (role: `penulis`)

### Key Commands
- **Tests**: `php artisan test` (PHPUnit; see [TESTING_GUIDE.md](TESTING_GUIDE.md) for scenarios)
- **Dev Mode**: `npm run dev` (watches CSS/JS; Tailwind 4 + Vite)
- **Concurrent Dev**: `composer run dev` (server + queue + logs + vite in parallel)
- **Linting**: `composer run pint` (Laravel Pint; auto-fix PHP formatting)
- **Migrations**: `php artisan migrate:fresh --seed` (reset DB; creates test data)

### File Organization by Role Controller
- **PenulisController** (`app/Http/Controllers/PenulisController.php`) → writer article CRUD
- **EditorController** (`app/Http/Controllers/EditorController.php`) → review queue, claim, publish, edit articles
- **AdminController** (`app/Http/Controllers/AdminController.php`) → user/article/master data CRUD
- **ArticleController** (`app/Http/Controllers/ArticleController.php`) → public-facing index & show

## 📋 Project-Specific Conventions

### Naming & Slug Generation
- Article slugs auto-generated from title (e.g., "Teknologi Sawit" → "teknologi-sawit")
- Controller methods follow pattern: `*Index()`, `*Create()`, `*Store()`, `*Edit()`, `*Update()`, `*Delete()` / `*Destroy()`
- All forms use `<form method="POST" action="{{ route('...') }}">` with `@csrf` + `@method('PUT/DELETE')`

### Logging & Audit Trail
- Every significant action logged via `LogService::record($action, $entityType, $entityId, $meta)`
  - Example: `LogService::record('article.submit', 'article', $article->id, ['old_status' => 'draft', 'new_status' => 'review'])`
- Logged actions: `article.create`, `article.submit`, `article.claimed`, `article.approved`, `article.rejected`, `article.delete`
- Access logs via `GET /admin/logs` → `LogController@logs()`

### Validation & File Handling
- Cover image uploads go to `public/articles/` (path: `storage_path('articles/')`)
- Fulltext search on articles uses MySQL FULLTEXT when available; falls back to LIKE
- Form validation uses **Rules classes** or inline array validation

### Testing Approach
- Feature tests in `tests/Feature/` (e.g., test article lifecycle per role)
- Unit tests for models/services in `tests/Unit/`
- Seed fresh DB for each test; use **DatabaseMigrations** trait
- Test file names: `{Feature|Unit}NameTest.php`; methods: `test*()` (e.g., `testPenulisCanCreateDraft()`)

## ⚙️ External Integrations & Dependencies

- **Laravel 12** (Eloquent, routing, middleware, validation)
- **Google OAuth** via Socialite (`laravel/socialite`); flow: redirect to Google → callback → create/update user
- **Tailwind CSS 4** (via Laravel Vite Plugin) for styling
- **Faker** (database/factories/) for test data generation
- **PHPUnit 11.5.3** for testing
- **MySQL** for fulltext search on articles (fallback: LIKE queries)

## 🔑 Key Files to Know

| File | Purpose |
|------|---------|
| [routes/web.php](routes/web.php) | Route definitions; study role-based groups |
| [app/Models/Article.php](app/Models/Article.php) | Article model; author/editor/category/tags relations |
| [app/Services/LogService.php](app/Services/LogService.php) | Centralized audit logging |
| [INTEGRATION_STATUS.md](INTEGRATION_STATUS.md) | Feature checklist (all features marked ✅) |
| [WORKFLOW.md](WORKFLOW.md) | Detailed workflow diagrams & state transitions |
| [TESTING_GUIDE.md](TESTING_GUIDE.md) | Test scenarios per role; credentials |

## 💡 Common Tasks & Patterns

### Adding a New Article Action
1. Define route in `web.php` (use named route, middleware `role:*`)
2. Add method to appropriate controller (PenulisController/EditorController/AdminController)
3. Log action: `LogService::record('article.action', 'article', $article->id, [...])`
4. Update `INTEGRATION_STATUS.md` & `WORKFLOW.md` if workflow-affecting

### Creating a Feature Test
```php
// tests/Feature/ArticleWorkflowTest.php
public function testPenulisCanSubmitArticle()
{
    $penulis = User::factory()->create(['role' => 'penulis']);
    $article = Article::factory()->create(['author_id' => $penulis->id, 'status' => 'draft']);
    
    $this->actingAs($penulis)
        ->post(route('penulis.articles.submit', $article), [])
        ->assertRedirect(route('penulis.articles.index'));
    
    $this->assertEquals('review', $article->fresh()->status);
}
```

### Querying Articles by Role
```php
// Penulis: own articles (all statuses)
$articles = Article::where('author_id', auth()->id())->get();

// Editor: review queue (unclaimed drafts)
$queue = Article::where('status', 'draft')->whereNull('editor_id')->get();

// Editor: own articles (review + published)
$articles = Article::where('editor_id', auth()->id())->whereIn('status', ['review', 'published'])->get();

// Public: published only
$articles = Article::where('status', 'published')->orderBy('published_at', 'desc')->get();
```

## 📝 When to Ask for Clarification

- Article status transitions outside the documented flow
- Cross-role permissions (e.g., can editors edit published articles?)
- Changes to seeded test data or credentials
- New external integrations beyond Google OAuth

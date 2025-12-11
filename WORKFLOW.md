# 📋 WORKFLOW INTEGRASI ARTIKEL - Penulis → Editor → Admin

## 🔄 ALUR LENGKAP SISTEM

```
┌─────────────────────────────────────────────────────────────────┐
│                     PENULIS FLOW                                 │
├─────────────────────────────────────────────────────────────────┤
│ 1. Buat Artikel Baru
│    URL: /penulis/articles/create
│    Status: draft (tidak ada editor_id)
│    Controller: PenulisController::articleCreate() → articleStore()
│
│ 2. Edit Artikel Draft
│    URL: /penulis/articles/{id}/edit
│    Hanya penulis pemilik yang bisa edit
│    Status tetap: draft
│
│ 3. SUBMIT ke Editor
│    URL: /penulis/articles/{id}/submit (POST)
│    Status berubah: draft → review
│    editor_id tetap NULL (belum diklaim)
│    Controller: PenulisController::articleSubmit()
│
│ 4. Tunggu Editor Klaim
│    Artikel masuk ke "Review Queue" editor
│    Penulis melihat status: Review (pending review)
│
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                     EDITOR FLOW                                  │
├─────────────────────────────────────────────────────────────────┤
│ 1. Review Queue
│    URL: /editor/reviews
│    Lihat SEMUA artikel dengan status=draft (unclaimed)
│    Controller: EditorController::reviewIndex()
│    Query: Article::where('status', 'draft')->with('author')
│
│ 2. KLAIM Artikel
│    URL: /editor/reviews/{id}/claim (POST)
│    Status: draft → review
│    editor_id: NULL → editor yang klaim
│    Controller: EditorController::claimArticle()
│
│ 3. Edit Artikel (List of Articles)
│    URL: /editor/articles
│    Hanya lihat artikel dengan status review & published
│    Tombol Edit hanya untuk status=review & editor=auth user
│    Controller: EditorController::articleIndex() & articleEdit()
│
│ 4. PUBLIKASIKAN Artikel
│    URL: /editor/articles/{id}/publish (PUT)
│    Status: review → published
│    published_at: sekarang
│    Controller: EditorController::articlePublish()
│
│ 5. Delete Artikel (Soft Delete)
│    URL: /editor/articles/{id} (DELETE)
│    Status tetap, tapi article di-soft delete
│    Controller: EditorController::articleDestroy()
│
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                     ADMIN FLOW                                   │
├─────────────────────────────────────────────────────────────────┤
│ 1. Lihat Semua Artikel
│    URL: /admin/articles
│    Lihat SEMUA artikel (draft, review, published)
│    Dengan filter status dan pencarian
│    Controller: AdminController::articleIndex()
│    Query: Article::with('author', 'editor', 'category', 'tags')
│
│ 2. Force Delete Artikel
│    URL: /admin/articles/{id} (DELETE)
│    Hapus permanen dari database
│    Controller: AdminController::articleDelete()
│    Catat di log: 'article.force_delete'
│
│ 3. Kategori & Tag Management
│    URL: /admin/masters/categories & /admin/masters/tags
│    Manage kategori dan tag untuk artikel
│
│ 4. Monitoring
│    Dashboard: Lihat statistik artikel, user, logs
│    URL: /admin
│    Hitung: total, published, draft, review
│
└─────────────────────────────────────────────────────────────────┘
```

## 📊 DATABASE STATE PER STATUS

### Status: `draft`
```
- Dibuat oleh: penulis
- editor_id: NULL
- published_at: NULL
- Bisa diedit oleh: penulis (pemilik)
- Bisa dihapus oleh: penulis (pemilik)
- Tempat lihat: Penulis (My Articles)
```

### Status: `review`
```
- Dibuat oleh: penulis (submit)
- editor_id: NULL atau <id_editor> (jika sudah diklaim)
- published_at: NULL
- Bisa diedit oleh: editor (yang mengklaim)
- Bisa diklaim oleh: editor mana saja (jika belum ada editor_id)
- Tempat lihat: Editor (Review Queue & List of Articles)
```

### Status: `published`
```
- Dibuat oleh: editor (publikasikan)
- editor_id: <id_editor> (editor yang publikasikan)
- published_at: timestamp (saat publikasi)
- Tidak bisa diedit oleh: siapa saja (LOCKED)
- Tempat lihat: Publik, Admin
```

## 🔑 KEY BUSINESS RULES

### ✅ Penulis

| Action | Status | Kondisi | Hasil |
|--------|--------|---------|-------|
| Create | - | - | Status = draft |
| Edit | draft | Pemilik | Tetap draft |
| Submit | draft | Pemilik | Status → review, editor_id = null |
| View | draft, review, published | Pemilik | Lihat di "My Articles" |
| Delete | draft | Pemilik | Hapus (soft delete) |

### ✅ Editor

| Action | Status | Kondisi | Hasil |
|--------|--------|---------|-------|
| View Queue | draft | - | Lihat unclaimed articles |
| Claim | draft | Belum diklaim | Status → review, editor_id = auth |
| Edit | review | Diklaim oleh auth | Edit konten |
| Publish | review | Diklaim oleh auth | Status → published, published_at = now |
| Delete | any | - | Soft delete |

### ✅ Admin

| Action | Kondisi | Hasil |
|--------|---------|-------|
| View All | - | Semua artikel semua status |
| Filter | By Status | Draft, Review, Published |
| Search | By Title | Cari judul artikel |
| Force Delete | - | Hard delete dari DB |

## 🛠️ CONTROLLER METHODS

### PenulisController
- `articleIndex()` - List artikel penulis (draft & submitted)
- `articleCreate()` - Form buat artikel
- `articleStore()` - Simpan artikel baru (status=draft)
- `articleEdit()` - Form edit artikel
- `articleUpdate()` - Update artikel (tetap draft)
- `articleSubmit()` - Submit untuk review (draft → review)
- `articleDelete()` - Hapus artikel penulis

### EditorController
- `reviewIndex()` - Antrian artikel draft unclaimed
- `claimArticle()` - Klaim artikel (draft → review + editor_id)
- `articleIndex()` - Daftar artikel review & published
- `articleEdit()` - Form edit artikel review
- `articleUpdate()` - Update artikel review
- `articlePublish()` - Publikasi artikel (review → published)
- `articleDestroy()` - Soft delete artikel

### AdminController
- `articleIndex()` - Lihat semua artikel dengan filter
- `articleDelete()` - Force delete artikel
- Jika artikel status ≠ `draft`:
  - Status tetap sama
  - Message: "Artikel diperbarui."

---

### 4. **Publik membaca artikel published**
```
GET  /                             → ArticleController::index()       [Daftar artikel published]
GET  /article/{slug}               → ArticleController::show()        [Baca artikel lengkap]
```
- Hanya artikel dengan `status='published'` yang tampil
- Fulltext search di judul & konten
- Pagination 10 artikel per halaman

---

## 🔐 Authentikasi & Roles

### Login/Register
```
GET  /login                        → AuthController::showLogin()
POST /login                        → AuthController::login()
GET  /register                     → AuthController::showRegister()
POST /register                     → AuthController::register() [Default role='penulis']
POST /logout                       → AuthController::logout()
```

### Google OAuth
```
GET  /auth/google                  → GoogleController::redirect()
GET  /auth/google/callback         → GoogleController::callback()
```

### Dashboard redirect per role
```
GET  /dashboard                    → redirect by role:
  - super_admin → /superadmin/users
  - editor      → /editor/review
  - penulis     → /penulis/articles
```

---

## 👤 Super Admin Routes

```
GET  /superadmin/users                 → SuperAdminController::dashboard()     [List users]
GET  /superadmin/users/{id}/edit       → SuperAdminController::editUser()      [Edit user]
POST /superadmin/users/{id}/update     → SuperAdminController::updateUser()    [Update user role/name]

GET  /superadmin/logs                  → SuperAdminController::logs()          [View logs]
POST /superadmin/logs/{id}/delete      → LogController::destroy()              [Delete log entry]
```

---

## 📊 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    PUBLIC                                    │
│                   (No Auth)                                  │
│  GET / (ArticleController::index)     → List published       │
│  GET /article/{slug}                  → Show article         │
└─────────────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────────────┐
│                  AUTHENTICATION                              │
│  Login/Register → Auth::attempt() → Dashboard redirect       │
└─────────────────────────────────────────────────────────────┘
         ↓
    ┌────┴────────┬────────────────┐
    ↓             ↓                ↓
┌─────────┐  ┌────────┐       ┌──────────┐
│ PENULIS │  │ EDITOR │       │SUPER ADMIN│
│(Author) │  │(Review)│       │  (Admin)  │
└────┬────┘  └────┬───┘       └─────┬────┘
     │            │                │
  [Routes]     [Routes]          [Routes]
  /penulis/   /editor/         /superadmin/
  - articles  - review         - users
  - create    - publish        - logs
  - edit      - sendback       - edit-user
  - update    
  - destroy   
     │            │                │
     └────────┬───┴────────────────┘
              ↓
    ┌─────────────────────┐
    │  LogService record  │
    │  - article.xxx      │
    │  - user.xxx         │
    │  - recorded to Logs  │
    └─────────────────────┘
```

---

## 🔄 Status Transisi Artikel

```
┌─────────┐
│  DRAFT  │  (Penulis: baru dibuat atau dikembalikan editor)
└────┬────┘
     │ (Penulis: siap submit)
     ↓
┌──────────┐
│  REVIEW  │  (Editor: menunggu review)
└────┬─────┘
     │
     ├─→ PUBLISHED  (Editor approve)
     │
     └─→ DRAFT      (Editor send back)
        ↑
        │ (Penulis: edit & resubmit → AUTO jadi REVIEW)
        └───────────────────
```

---

## ✅ Controllers & Methods

### ArticleController (Publik saja)
- `index()` - List published articles + search
- `show()` - Show single published article

### PenulisController (Author CRUD)
- `index()` - List author's articles
- `create()` - Form buat artikel
- `store()` - Submit artikel baru (status='review')
- `edit()` - Form edit artikel
- `update()` - Update artikel (auto-set status='review' jika dari 'draft')
- `destroy()` - Hapus artikel

### EditorController (Review)
- `reviewList()` - List artikel dengan status='review'
- `publish()` - Publish artikel (set status='published', published_at, editor_id)
- `sendBackToAuthor()` - Kembalikan artikel (set status='draft', editor_id)

### SuperAdminController (Admin)
- `dashboard()` - List users
- `editUser()` - Form edit user
- `updateUser()` - Update user name/role
- `logs()` - View activity logs

### LogController
- `destroy()` - Delete log entry

---

## 🎯 Middleware & Guards

- `auth` - Require logged in user
- `role:penulis|editor|super_admin` - Check user role
- `Authenticate` - Custom middleware untuk auth check
- `RoleMiddleware` - Custom middleware untuk role check

---

## 📝 Notes

1. **ArticleController** hanya untuk public routes (index, show)
2. **PenulisController** handle semua CRUD untuk author
3. **EditorController** handle review & approval workflow
4. **SuperAdminController** handle user management & logs
5. **Status auto-transition** di PenulisController::update() ketika dari 'draft' → 'review'
6. **Authorization** di setiap controller method untuk owner check
7. **Logging** di LogService untuk setiap action penting

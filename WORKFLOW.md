# Alur Workflow AgroGISTech

## 📋 Workflow Artikel (Article Workflow)

### 1. **Penulis (Author) membuat artikel baru**
```
GET  /penulis/create               → PenulisController::create()      [Form create]
POST /penulis/store                → PenulisController::store()       [Submit → status='review']
```
- Penulis mengisi form dan submit
- Artikel disimpan dengan **status='review'**
- LogService mencatat: `article.submit_for_review`
- Redirect ke `/penulis/articles` dengan pesan sukses

---

### 2. **Editor mereview artikel (status='review')**
```
GET  /editor/review                → EditorController::reviewList()   [List review articles]
POST /editor/publish/{article}     → EditorController::publish()      [Publish]
POST /editor/sendback/{article}    → EditorController::sendBackToAuthor() [Tolak, kirim balik]
```

**Opsi A: Editor Publish**
- Status berubah: `review` → `published`
- Set `published_at = now()`
- Set `editor_id = Auth::id()` (editor yang publish)
- LogService mencatat: `article.publish`
- Artikel muncul di halaman publik

**Opsi B: Editor Kirim Balik**
- Status berubah: `review` → `draft`
- Set `editor_id = Auth::id()` (editor yang kirim balik)
- LogService mencatat: `article.send_back` + note
- Penulis bisa edit dan resubmit

---

### 3. **Penulis menerima feedback dan mengedit ulang**
```
GET  /penulis/articles             → PenulisController::index()       [List artikel penulis]
GET  /penulis/articles/{id}/edit   → PenulisController::edit()        [Form edit]
PUT  /penulis/articles/{id}        → PenulisController::update()      [Submit update]
```

**Saat update:**
- Jika artikel status = `draft` (hasil kirim balik editor):
  - Status otomatis berubah → `review`
  - Message: "Artikel diperbarui dan dikirim untuk direview."
  - Balik ke editor untuk review ulang
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

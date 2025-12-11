# 🧪 TESTING GUIDE - Article Workflow Integration

## 👥 USER CREDENTIALS (dari Seeder)

```
PENULIS:
  Email: penulis@example.com
  Password: 123456
  Role: penulis
  ID: 3

EDITOR:
  Email: editor@example.com
  Password: 123456
  Role: editor
  ID: 2

ADMIN:
  Email: admin@example.com
  Password: 123456
  Role: super_admin
  ID: 1
```

---

## 📊 DATABASE STATE AWAL

Setelah `migrate:fresh --seed`, database berisi:

```
ARTICLES:
  ID | Title                                          | Status     | Author | Editor
  ---|------------------------------------------------|------------|--------|--------
  1  | Teknologi Pemupukan Modern pada Kelapa Sawit   | published  | 3      | 2
  2  | Inovasi Irigasi Presisi untuk Perkebunan       | draft      | 3      | null
  3  | Manajemen Hama Terpadu                         | review     | 3      | 2
  4  | Sawit Modern - Teknik Terbaru                  | draft      | 3      | null
```

---

## 🧪 TEST SCENARIO 1: Full Article Lifecycle

### 1.1 Penulis Create Artikel Baru
**Login sebagai:** `penulis@example.com` / `123456`

1. Buka: `/penulis/articles`
2. Klik: "Buat Artikel Baru" button
3. Isi form:
   - Title: "Inovasi Teknologi Pertanian 2025"
   - Content: "Lorem ipsum dolor sit amet... (minimal 10 kata)"
   - Cover Image: (optional)
4. Klik: "Simpan sebagai Draft"
5. **Expected Result:**
   - Redirect ke `/penulis/articles`
   - Pesan: "Artikel berhasil disimpan sebagai draf."
   - Artikel muncul di list dengan status `Draft`
   - Tombol: Edit, Ajukan, Hapus tersedia

### 1.2 Penulis Edit Artikel
1. Di halaman list artikel, klik `Edit` pada artikel yang baru dibuat
2. Update content/title
3. Klik: "Update"
4. **Expected Result:**
   - Status tetap `Draft`
   - Content/title updated
   - Pesan: "Artikel berhasil diperbarui."

### 1.3 Penulis Submit Artikel untuk Review
1. Di halaman list, klik `Ajukan` pada artikel draft
2. Confirm dialog: "Ajukan artikel untuk di-review?"
3. Klik: "OK"
4. **Expected Result:**
   - Status berubah: `Draft` → `Review`
   - Tombol: Edit, Ajukan diganti dengan "Terkunci"
   - Pesan: "Artikel berhasil diajukan untuk di-review oleh editor."
   - Log tercatat: `article.submit`

### 1.4 Editor Lihat Review Queue
**Login sebagai:** `editor@example.com` / `123456`

1. Buka: `/editor/reviews` (atau klik "Review Queue" di sidebar)
2. **Expected Result:**
   - Lihat artikel #2 dan #4 (draft yang unclaimed)
   - Lihat artikel baru dari penulis (status draft)
   - Tampil: Title, Writer, Status, Action (Claim & Get Started)

### 1.5 Editor Klaim Artikel
1. Klik: "Klaim & Get Started" pada artikel yang disubmit penulis
2. Confirm: "Klaim artikel ini untuk diedit? Status akan berubah menjadi In Review."
3. Klik: "OK"
4. **Expected Result:**
   - Redirect ke `/editor/articles`
   - Pesan: "Anda berhasil mengklaim artikel ini. Silakan lanjutkan penyuntingan di Daftar Artikel."
   - Artikel sekarang terlihat di "Daftar Artikel" dengan status `Review`
   - Log tercatat: `article.claimed`

### 1.6 Editor Edit Artikel
1. Di halaman "Daftar Artikel", klik `Edit` pada artikel yang diklaim
2. Edit content
3. Klik: "Simpan Perubahan"
4. **Expected Result:**
   - Status tetap `Review`
   - Content updated
   - Pesan: "Artikel berhasil diperbarui."
   - Tombol: "Publikasikan" tersedia

### 1.7 Editor Publikasikan Artikel
1. Di halaman "Daftar Artikel", lihat artikel dengan status `Review`
2. Klik: "Publikasikan" (atau di dalam edit form)
3. Confirm: "Publikasikan artikel ini?"
4. Klik: "OK"
5. **Expected Result:**
   - Status berubah: `Review` → `Published`
   - `published_at` terisi dengan waktu sekarang
   - Pesan: "Artikel berhasil dipublikasikan."
   - Artikel hilang dari editor's review list
   - Artikel muncul di halaman publik
   - Log tercatat: `article.published`

### 1.8 Admin Lihat Semua Artikel
**Login sebagai:** `admin@example.com` / `123456`

1. Buka: `/admin` (Dashboard)
2. **Expected Result:**
   - Lihat statistics: Total articles, Penulis, etc.
3. Klik: "Manajemen Artikel" atau buka `/admin/articles`
4. **Expected Result:**
   - Lihat SEMUA artikel (draft, review, published)
   - Tampil: Title, Author, Category, Status
   - Filter buttons: Semua, Diterbitkan, Draf, Review
   - Tombol: Delete (Force Delete)

---

## 🧪 TEST SCENARIO 2: Multiple Editors

### 2.1 Create Additional Editor User
**Login sebagai:** `admin@example.com`

1. Buka: `/admin/users`
2. Klik: "Tambah User Baru"
3. Isi:
   - Name: "Editor Kedua"
   - Email: "editor2@example.com"
   - Password: "123456"
   - Role: "editor"
4. Klik: "Buat User"
5. **Expected Result:**
   - User baru created
   - Pesan: "Pengguna baru berhasil ditambahkan"

### 2.2 Both Editors Dapat Lihat Draft
**Editor 1** dan **Editor 2** login:
1. Buka `/editor/reviews`
2. **Expected Result:**
   - Kedua editor lihat SEMUA draft articles
   - Bisa claim artikel yang belum diklaim

### 2.3 First Editor Claim, Second Editor Cannot Claim
**Editor 1:**
1. Klaim artikel #2

**Editor 2:**
1. Buka `/editor/reviews`
2. Lihat artikel #2
3. Klik "Claim & Get Started"
4. **Expected Result:**
   - Error: "Artikel sudah diklaim oleh editor lain."
   - Tidak bisa claim

---

## 🧪 TEST SCENARIO 3: Search & Filter

### 3.1 Penulis Filter by Status
**Login sebagai:** `penulis@example.com`

1. Buka: `/penulis/articles`
2. Pilih dropdown: "Status" → "Draf"
3. Klik: "Filter"
4. **Expected Result:**
   - Hanya artikel dengan status Draft ditampilkan

### 3.2 Penulis Search by Title
1. Input text field: "inovasi"
2. Klik: "Filter"
3. **Expected Result:**
   - Hanya artikel dengan title mengandung "inovasi" ditampilkan

### 3.3 Admin Filter & Search
**Login sebagai:** `admin@example.com`

1. Buka: `/admin/articles`
2. Pilih filter buttons (Semua, Diterbitkan, Draf, Review)
3. **Expected Result:**
   - Artikel di-filter sesuai button yang diklik

---

## 🧪 TEST SCENARIO 4: Delete Operations

### 4.1 Penulis Delete Draft
**Login sebagai:** `penulis@example.com`

1. Buka: `/penulis/articles`
2. Klik: "Hapus" pada artikel draft
3. Confirm: "Hapus draf ini?"
4. **Expected Result:**
   - Artikel dihapus (soft delete)
   - Pesan: "Artikel berhasil dihapus."
   - Log tercatat: `article.delete`

### 4.2 Admin Force Delete
**Login sebagai:** `admin@example.com`

1. Buka: `/admin/articles`
2. Klik: "Hapus" pada artikel apa saja
3. Confirm: "Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini permanen (Force Delete)."
4. **Expected Result:**
   - Artikel dihapus permanent dari DB (hard delete)
   - Pesan: "Artikel 'XXX' dihapus paksa."
   - Log tercatat: `article.force_delete`

---

## 🧪 TEST SCENARIO 5: Category & Tags

### 5.1 Admin Create Category
**Login sebagai:** `admin@example.com`

1. Buka: `/admin/masters/categories`
2. Klik: "Tambah Kategori"
3. Input: Name: "Teknologi Terbaru"
4. Klik: "Simpan"
5. **Expected Result:**
   - Kategori created
   - Slug auto-generated: "teknologi-terbaru"

### 5.2 Editor Assign Category to Article
**Login sebagai:** `editor@example.com`

1. Buka: `/editor/articles`
2. Klik: "Edit" pada artikel yang ada
3. Pilih: Category dropdown
4. Klik: "Simpan Perubahan"
5. **Expected Result:**
   - Kategori assigned
   - Artikel sekarang tampil dengan category badge

### 5.3 Penulis Lihat Category di Artikel
**Login sebagai:** `penulis@example.com` atau **Public**

1. Lihat artikel yang sudah assigned kategori
2. **Expected Result:**
   - Kategori ditampilkan sebagai badge/chip

---

## ✅ VALIDATION TESTS

### Test: Unauthorized Access

**Penulis tidak bisa akses editor endpoints:**
1. Penulis login
2. Coba akses: `/editor/reviews`
3. **Expected Result:** 403 Unauthorized atau redirect

**Editor tidak bisa akses admin endpoints:**
1. Editor login
2. Coba akses: `/admin/articles`
3. **Expected Result:** 403 Unauthorized atau redirect

### Test: Authorization on Edit

**Penulis A tidak bisa edit artikel Penulis B:**
1. Login sebagai Penulis
2. Try to edit artikel punya user lain (direct URL)
3. **Expected Result:** 403 Forbidden, pesan: "Anda tidak memiliki akses ke artikel ini."

**Editor tidak bisa edit artikel yang diklaim editor lain:**
1. Editor A klaim artikel
2. Editor B coba edit artikel tersebut
3. **Expected Result:** Tombol Edit disabled atau 403

---

## 📝 LOGGING VERIFICATION

Semua action harus tercatat di table `logs`:

**Check di Admin → Logs:**
```
article.create       → Penulis buat artikel
article.submit       → Penulis submit untuk review
article.claimed      → Editor klaim artikel
article.update       → Editor/Penulis update
article.published    → Editor publikasikan
article.delete       → Soft delete (penulis/editor)
article.force_delete → Hard delete (admin)
user.create          → User dibuat (admin)
```

---

## 🔍 TROUBLESHOOTING

### Artikel tidak muncul di editor review queue
- Check: Artikel status = `draft`?
- Check: Artikel editor_id = NULL?
- Check: User role = editor?
- Fix: Reset DB dengan `php artisan migrate:fresh --seed`

### Cannot edit artikel
- Check: User = penulis owner? (untuk draft)
- Check: User = editor yang klaim? (untuk review)
- Check: Tidak published?
- Solution: Pastikan condition di controller benar

### Foreign key constraint error
- Cause: Categories/Tags tidak punya column `id`
- Check: Migration file `2025_12_02_042904` dan `2025_12_02_043220`
- Fix: Pastikan `$table->id()` bukan `$table->bigIncrements('category_id')`

---

## 🎯 SUCCESS CRITERIA

Semua test scenario di atas HARUS PASS ✅

Ketika semua passing:
- ✅ Workflow complete penulis → editor → admin
- ✅ Data terintegrasi di database
- ✅ Logging semua aksi
- ✅ Authorization & authentication work
- ✅ UI responsive & user-friendly

**SISTEM READY FOR PRODUCTION! 🚀**

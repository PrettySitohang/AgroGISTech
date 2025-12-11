## ✅ PERBAIKAN COMPLETE - Database dan Migrations

### MASALAH YANG DITEMUKAN DAN DIPERBAIKI

#### 1. **Migration Issues**
- `categories` dan `tags` menggunakan custom primary keys (`category_id`, `tag_id`) 
- Foreign keys tidak bisa direferensikan karena column `id` tidak ada
- Migration `2025_12_04_134913_add_category_id_to_articles` juga perlu check column exists

#### 2. **Status Artikel Salah**
- Sebelum: Tidak ada artikel draft (semua review/published)
- Setelah: 2 artikel draft, 1 review, 1 published ✓

#### 3. **Seeder Data**
- ArticleSeeder sudah benar, membuat data dengan status yang tepat

### SOLUSI DITERAPKAN

#### A. Perbaiki Migrations
**File: `2025_12_02_042904_create_table_category.php`**
```php
// BEFORE:
$table->bigIncrements('category_id');

// AFTER:
$table->id();
```

**File: `2025_12_02_043220_create_table_tag.php`**
```php
// BEFORE:
$table->bigIncrements('tag_id');

// AFTER:
$table->id();
```

**File: `2025_12_04_134913_add_category_id_to_articles.php`**
```php
// Tambah check jika column sudah ada
if (!Schema::hasColumn('articles', 'category_id')) {
    $table->foreignId('category_id')...
}
```

#### B. Reset Database
```bash
php artisan migrate:fresh --seed
```

### DATABASE FINAL STATE

| ID | Title | Status | Author | Editor | Created |
|----|-------|--------|--------|--------|---------|
| 1 | Teknologi Pemupukan Modern pada Kelapa Sawit | **published** | 3 | 2 | Seeder |
| 2 | Inovasi Irigasi Presisi untuk Perkebunan | **draft** | 3 | null | Seeder |
| 3 | Manajemen Hama Terpadu | **review** | 3 | 2 | Seeder |
| 4 | Sawit Modern - Teknik Terbaru | **draft** | 3 | null | Seeder |

### WORKFLOW YANG BENAR

```
PENULIS FLOW:
1. Penulis membuat artikel → Status: draft
2. Penulis edit artikel (tetap draft, tidak ada editor)
3. Penulis klik "Submit untuk Review" → Status: review (editor_id: null)

EDITOR FLOW:
1. Editor buka "Antrian Review" → Lihat artikel dengan status=draft
2. Editor klik "Klaim & Mulai" → Status: draft → review, editor_id: terisi
3. Editor edit artikel di "Daftar Artikel" → Status tetap: review
4. Editor publikasikan → Status: published
```

### FILES YANG DIMODIFIKASI

1. ✅ `database/migrations/2025_12_02_042904_create_table_category.php` - Ganti `category_id` menjadi `id()`
2. ✅ `database/migrations/2025_12_02_043220_create_table_tag.php` - Ganti `tag_id` menjadi `id()`
3. ✅ `database/migrations/2025_12_04_134913_add_category_id_to_articles.php` - Tambah check column exists
4. ✅ Database direset dengan `migrate:fresh --seed`

### CARA TESTING

**User Credentials:**
- **Penulis**: username = `penulis`, password = `123456`
- **Editor**: username = `editor`, password = `123456`

**Test Flow 1: Editor Melihat Antrian Draft**
1. Login sebagai Editor
2. Buka "Review Queue" → Akan melihat artikel #2 & #4 dengan status `Draft`
3. Klik "Claim & Get Started" pada artikel #2

**Test Flow 2: Editor Edit Artikel**
1. Setelah claim, akan redirect ke "List of Articles"
2. Lihat artikel #2 sekarang status `Review` dengan editor terisi
3. Klik "Edit" untuk mengubah konten

**Test Flow 3: Penulis Submit Draft**
1. Login sebagai Penulis
2. Buka artikel draft dari "My Articles"
3. Edit dan klik "Submit untuk Review" → Status: draft → review

### CATATAN PENTING

- Tidak boleh ada artikel dengan status `draft` yang memiliki `editor_id` (editor hanya terisi saat claim di status review)
- Hanya editor yang mengklaim yang bisa edit di "List of Articles"
- Seeder membuat user dengan role `penulis` (ID 3) dan `editor` (ID 2)


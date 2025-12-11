# 🔐 Google OAuth Setup Guide - AgroGISTech

## Overview

Panduan lengkap untuk mengatur Google OAuth login di AgroGISTech sehingga user bisa login dengan akun Google mereka.

## ❌ Error yang Anda Alami

```
Access blocked: Authorization Error
The OAuth client was not found.
Error 401: invalid_client
```

**Penyebab:** Google OAuth credentials belum dikonfigurasi di `.env` file.

---

## ✅ Langkah-Langkah Setup (LENGKAP)

### **Step 1: Akses Google Cloud Console**

1. Buka browser dan pergi ke: https://console.cloud.google.com/
2. Login dengan akun Google Anda
3. Jika belum ada project, klik **Select a Project** → **New Project**

### **Step 2: Buat Project Baru**

1. Pada dialog "Create Project":
   - **Project name:** `AgroGISTech`
   - **Organization:** (skip jika tidak ada)
2. Klik **Create**
3. Tunggu sampai project dibuat (2-3 menit)

### **Step 3: Enable Google+ API**

1. Setelah project dibuat, di bagian search atas, cari: **`Google+ API`**
2. Klik pada hasil search
3. Klik tombol **ENABLE**

### **Step 4: Setup OAuth Consent Screen**

1. Di sidebar kiri, klik **APIs & Services** → **OAuth consent screen**
2. Pilih **External** sebagai User Type
3. Klik **Create**
4. Isi form:
   - **App name:** AgroGISTech
   - **User support email:** email Anda
   - **Developer contact information:** email Anda
5. Klik **Save and Continue**
6. Di bagian "Scopes", klik **Add or Remove Scopes**
   - Cari dan tambahkan: `email`, `profile`, `openid`
   - Klik **Update**
7. Klik **Save and Continue** → **Back to Dashboard**

### **Step 5: Buat OAuth 2.0 Credentials**

1. Di sidebar, klik **Credentials**
2. Klik **+ Create Credentials** → **OAuth client ID**
3. Jika muncul error, klik dulu **+ Create Credentials** → **OAuth consent screen** (ulangi Step 4)
4. Pilih **Application type:** `Web application`
5. Isi:
   - **Name:** AgroGISTech OAuth Client
   - **Authorized JavaScript origins:**
     ```
     http://localhost:8000
     http://127.0.0.1:8000
     ```
   - **Authorized redirect URIs:**
     ```
     http://localhost:8000/auth/google/callback
     http://127.0.0.1:8000/auth/google/callback
     ```
6. Klik **Create**

### **Step 6: Copy Credentials**

Setelah klik Create, akan muncul modal dengan:
- **Client ID** (format: `xxxxx.apps.googleusercontent.com`)
- **Client Secret** (string panjang)

**COPY KEDUA VALUE INI!**

### **Step 7: Update `.env` File**

1. Buka file `.env` di root project (di VS Code atau editor lain)
2. Cari baris:
   ```
   GOOGLE_CLIENT_ID=your-client-id-dari-google-cloud.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=your-client-secret-dari-google-cloud
   GOOGLE_CALLBACK=http://localhost:8000/auth/google/callback
   ```
3. Ganti dengan credential Anda:
   ```
   GOOGLE_CLIENT_ID=123456789.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnop
   GOOGLE_CALLBACK=http://localhost:8000/auth/google/callback
   ```
4. **SAVE** file

### **Step 8: Clear Cache Laravel**

Jalankan di terminal:
```bash
php artisan config:cache
php artisan cache:clear
```

---

## 🧪 Test Login

1. Buka aplikasi: http://localhost:8000
2. Klik tombol **Sign in with Google**
3. Login dengan akun Google Anda
4. Aplikasi akan membuat user account otomatis
5. Selamat! ✅

---

## 🔗 File-File yang Terlibat

| File | Fungsi |
|------|--------|
| `.env` | Menyimpan Google OAuth credentials |
| `config/services.php` | Konfigurasi Google OAuth di Laravel |
| `app/Http/Controllers/GoogleController.php` | Logic untuk Google login |
| `routes/web.php` | Route untuk Google callback |

---

## 📝 Troubleshooting

### Error: "The OAuth client was not found"
- **Solusi:** Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env` sudah benar
- Jalankan: `php artisan config:cache`

### Error: "Redirect URI mismatch"
- **Solusi:** Pastikan URL di Google Console sama dengan yang di `.env`
- Jika development di `localhost:8000`, URL harus persis sama di Console

### Error: "Access denied"
- **Solusi:** Pastikan OAuth consent screen sudah di-publish untuk External users

### Can't login ke Google Cloud Console
- **Solusi:** Gunakan akun Google pribadi (bukan corporate email jika ada batasan)

---

## ✨ Keamanan

- ❌ Jangan commit `.env` ke git (sudah di `.gitignore`)
- ❌ Jangan share `GOOGLE_CLIENT_SECRET` ke siapapun
- ✅ Simpan credentials dengan aman
- ✅ Gunakan environment variables di production

---

## Production Setup

Saat deploy ke production:

1. Buat project Google Cloud baru (atau pakai existing)
2. Tambahkan production URL di OAuth credentials:
   ```
   https://yourdomain.com
   https://yourdomain.com/auth/google/callback
   ```
3. Update `.env` di server dengan production credentials
4. Restart application

---

## Bantuan Lebih Lanjut

- Google OAuth Documentation: https://developers.google.com/identity/protocols/oauth2
- Laravel Socialite: https://laravel.com/docs/socialite

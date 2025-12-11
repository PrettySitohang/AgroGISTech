# 🌓 Dark Mode / Light Mode Implementation Guide

## Overview

AgroGISTech sekarang mendukung **Dark Mode** dan **Light Mode** di semua halaman aplikasi (Admin, Editor, Penulis, dan Public). Mode ini dapat di-toggle dengan mudah menggunakan button di header, dan preferensi user akan disimpan di localStorage.

## Features

✅ **Automatic Dark Mode Detection** - Sistem mendeteksi preferensi OS user  
✅ **Persistent Theme Storage** - Pilihan user disimpan di localStorage  
✅ **Smooth Transitions** - Transisi warna yang halus saat toggle  
✅ **Tailwind Dark Mode Support** - Menggunakan `dark:` utility classes  
✅ **Custom Color Palette** - Warna custom untuk light mode  

## How It Works

### 1. **Theme Toggle Button**

Setiap layout memiliki button untuk toggle theme di bagian atas:

```html
<button onclick="window.toggleTheme()" class="p-2 rounded-full ...">
    <i class="fas fa-sun hidden dark:block"></i>  <!-- Muncul saat dark mode -->
    <i class="fas fa-moon block dark:hidden"></i>  <!-- Muncul saat light mode -->
</button>
```

### 2. **Available Functions**

```javascript
// Toggle antara dark dan light mode
window.toggleTheme()

// Dapatkan theme saat ini
window.getCurrentTheme()  // Returns: 'dark' atau 'light'

// Set theme ke mode spesifik
window.setTheme('dark')   // Ubah ke dark mode
window.setTheme('light')  // Ubah ke light mode
```

### 3. **Color Scheme**

#### Dark Mode (Default)
- Background: `#1C0E0B` (bg-dark)
- Text: `#F5F5DC` (cream-text)
- Primary Accent: `#D36B5E` (terracotta)
- Secondary: `#8B3A2C` (sienna)

#### Light Mode
- Background: `#F5F5F5` (bg-light)
- Text: `#1F2937` (light-text)
- Primary Accent: `#D36B5E` (terracotta) - sama
- Secondary: `#8B3A2C` (sienna) - sama

## Usage in Templates

### Using `dark:` Prefix (Tailwind)

```blade
<!-- Akan berwarna cream-text di dark mode, light-text di light mode -->
<h1 class="text-cream-text light:text-light-text">Title</h1>

<!-- Background yang berbeda per mode -->
<div class="bg-bg-dark light:bg-bg-light">Content</div>

<!-- Border color per mode -->
<div class="border border-sienna/30 light:border-gray-300">Content</div>
```

### Key Pattern

Gunakan format ini untuk semua elemen:
```blade
class="[dark-mode-class] light:[light-mode-class]"
```

## Locations with Theme Toggle

✅ **Admin Dashboard** - `/admin`  
✅ **Editor Dashboard** - `/editor`  
✅ **Public/Penulis** - `/` dan semua halaman public  
✅ **Header** - Di semua layout dengan icon sun/moon  

## Custom Theme Event

Ketika user toggle theme, custom event dipanggil:

```javascript
document.addEventListener('themeChanged', (e) => {
    console.log('Theme changed to:', e.detail.isDark ? 'dark' : 'light');
    // Lakukan refresh untuk charts, maps, dll jika diperlukan
});
```

## Files Involved

| File | Purpose |
|------|---------|
| [resources/js/darkMode.js](resources/js/darkMode.js) | Dark mode logic (localStorage, toggle, events) |
| [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) | Public layout with dark mode |
| [resources/views/admin/layouts/app.blade.php](resources/views/admin/layouts/app.blade.php) | Admin layout with dark mode |
| [resources/views/editor/layouts/app.blade.php](resources/views/editor/layouts/app.blade.php) | Editor layout with dark mode |
| [resources/views/layouts/partials/header.blade.php](resources/views/layouts/partials/header.blade.php) | Toggle button di header |

## Testing Dark Mode

1. Buka aplikasi di browser
2. Klik button **Sun** (☀️) / **Moon** (🌙) di header
3. Halaman akan berubah warna dengan smooth transition
4. Refresh halaman → theme preference tetap tersimpan
5. Coba login ke different roles (admin, editor, penulis) → theme tetap konsisten

## Browser Support

✅ Chrome/Edge 90+  
✅ Firefox 88+  
✅ Safari 14+  
✅ Mobile browsers (iOS Safari, Chrome Mobile)  

## Known Limitations

- Charts (Chart.js) mungkin perlu custom handling untuk smooth dark mode transition
- Beberapa components eksternal mungkin tidak fully support dark mode
- System theme preference hanya digunakan jika user belum menyimpan preferensi

## Future Improvements

- [ ] Simpan theme preference di database per user
- [ ] Add auto-switch based on time (siang = light, malam = dark)
- [ ] Improve chart colors saat mode berubah
- [ ] Theme customization di admin settings

## Example: Adding Dark Mode Support ke New Components

```blade
<!-- Gunakan conditional classes dengan light: prefix -->
<div class="bg-bg-dark border border-sienna/30 light:bg-white light:border-gray-300">
    <h2 class="text-cream-text light:text-light-text">Title</h2>
    <p class="text-gray-400 light:text-gray-600">Subtitle</p>
</div>
```

Pastikan setiap elemen yang visible di light mode memiliki `light:` variant!

/**
 * Dark Mode / Light Mode Toggle Script
 * Dapat digunakan di semua halaman aplikasi
 */

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    initTheme();
});

/**
 * Initialize theme based on localStorage atau system preference
 */
function initTheme() {
    const theme = localStorage.getItem('theme');
    
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else if (theme === 'light') {
        document.documentElement.classList.remove('dark');
    }
}

/**
 * Toggle between dark dan light mode
 */
window.toggleTheme = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Dispatch custom event untuk notifikasi perubahan tema
    document.dispatchEvent(new CustomEvent('themeChanged', {
        detail: { isDark: isDark }
    }));
    
    return isDark;
};

/**
 * Get current theme mode
 * @returns {string} 'dark' atau 'light'
 */
window.getCurrentTheme = function() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
};

/**
 * Set theme to specific mode
 * @param {string} mode - 'dark' atau 'light'
 */
window.setTheme = function(mode) {
    if (mode === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else if (mode === 'light') {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
    
    document.dispatchEvent(new CustomEvent('themeChanged', {
        detail: { isDark: mode === 'dark' }
    }));
};

// Listen untuk perubahan system theme preference
const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
darkModeMediaQuery.addEventListener('change', (e) => {
    // Hanya auto-switch jika user belum menyimpan preferensi
    if (!localStorage.getItem('theme')) {
        if (e.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});

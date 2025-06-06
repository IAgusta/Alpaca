// Global settings manager
const SettingsManager = (function() {
    const defaults = {
        ui_language: 'en',
        color_scheme: 'default',
        image_quality: 'medium',
        experimental_features: false
    };

    // Get current settings
    function getSettings() {
        const saved = localStorage.getItem('userPreferences');
        return saved ? JSON.parse(saved) : {...defaults};
    }

    // Save settings and apply globally
    function saveSettings(settings) {
        localStorage.setItem('userPreferences', JSON.stringify(settings));
        applyToPage(settings);
        document.dispatchEvent(new CustomEvent('settingsChanged', { detail: settings }));
    }

    // Helper: Update theme images (only on about page)
    function updateThemeImages(isDark) {
        // Only run if on about page
        if (!window.location.pathname.match(/about/i)) return;
        document.querySelectorAll('[data-theme-img]').forEach(img => {
            const base = img.getAttribute('data-theme-img');
            // If original src ends with .png, .jpg, etc, keep extension
            let ext = '.png';
            const match = img.src.match(/\.(png|jpg|jpeg|webp|gif)$/i);
            if (match) ext = match[0];
            // Special case: if original src is .png or .jpg, use that extension
            // If the base already includes extension, remove it
            const baseNoExt = base.replace(/\.(png|jpg|jpeg|webp|gif)$/i, '');
            // If the image is profile_management.png (no -light/-dark), skip
            if (img.src.includes('profile_management.png')) return;
            img.src = baseNoExt + (isDark ? '-dark' : '-light') + ext;
        });
    }

    // Apply settings to the current page
    function applyToPage(settings = null) {
        const currentSettings = settings || getSettings();
        
        // Apply color scheme
        const isDark = currentSettings.color_scheme === 'dark' || 
                      (currentSettings.color_scheme === 'system' && 
                       window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        document.documentElement.classList.toggle('dark', isDark);
        
        // Apply other global settings
        document.documentElement.dataset.imageQuality = currentSettings.image_quality;
        document.documentElement.lang = currentSettings.ui_language;

        // Update theme images
        updateThemeImages(isDark);
    }

    // Initialize settings - should run on every page
    function init() {
        applyToPage();
        
        // Watch for system theme changes if scheme is 'system'
        const settings = getSettings();
        if (settings.color_scheme === 'system') {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                applyToPage();
            });
        }
    }

    return {
        getSettings,
        saveSettings,
        applyToPage,
        init
    };
})();

// Initialize settings on every page load
SettingsManager.init();

// Settings page specific code - only runs on the settings page
if (document.getElementById('settingsForm')) {
    document.addEventListener('DOMContentLoaded', function() {
        const currentSettings = SettingsManager.getSettings();
        
        // Initialize form values
        document.getElementById('ui_language').value = currentSettings.ui_language;
        document.querySelector(`input[name="color_scheme"][value="${currentSettings.color_scheme}"]`).checked = true;
        document.querySelector(`input[name="image_quality"][value="${currentSettings.image_quality}"]`).checked = true;
        document.querySelector('input[name="experimental_features"]').checked = currentSettings.experimental_features;

        // Handle form submission
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newSettings = {
                ui_language: document.getElementById('ui_language').value,
                color_scheme: document.querySelector('input[name="color_scheme"]:checked').value,
                image_quality: document.querySelector('input[name="image_quality"]:checked').value,
                experimental_features: document.querySelector('input[name="experimental_features"]').checked
            };
            
            SettingsManager.saveSettings(newSettings);
            
            // Show feedback using input-success component
            const successDiv = document.createElement('div');
            successDiv.innerHTML = `
                <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4">
                    <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">Settings saved and applied globally!</div>
                    </div>
                </div>
            `;
            document.body.appendChild(successDiv);
            setTimeout(() => successDiv.remove(), 5000);
        });
    });
}

// Make available globally if needed
window.SettingsManager = SettingsManager;
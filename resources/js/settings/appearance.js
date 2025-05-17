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
            
            // Show feedback using Toast or similar
            const toast = document.createElement('div');
            toast.textContent = 'Settings saved and applied globally!';
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        });
    });
}

// Make available globally if needed
window.SettingsManager = SettingsManager;
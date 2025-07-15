// Applies all settings (theme, lang, finder visibility)
function applyUserPreferences(settings) {
    // Theme
    if (
        settings.color_scheme === 'dark' ||
        (settings.color_scheme === 'system' &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Language and image quality
    document.documentElement.dataset.imageQuality = settings.image_quality;
    document.documentElement.lang = settings.ui_language;

    // Show or hide Finder sub-brand
    const subBrand = document.getElementById('subBrand');
    const subBrandMobile = document.getElementById('subBrandMobile');
    if (settings.experimental_features) {
        if (subBrand) subBrand.classList.remove('hidden');
        if (subBrandMobile) subBrandMobile.classList.remove('hidden');
    } else {
        if (subBrand) subBrand.classList.add('hidden');
        if (subBrandMobile) subBrandMobile.classList.add('hidden');
    }
}

// Immediately apply saved settings on load
(function () {
    const savedSettings = localStorage.getItem('userPreferences');
    const settings = savedSettings
        ? JSON.parse(savedSettings)
        : {
              color_scheme: 'system',
              ui_language: 'en',
              image_quality: 'medium',
              experimental_features: false
          };

    // Apply everything immediately
    applyUserPreferences(settings);
})();

// Handle settings form
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('settingsForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const settings = {
            color_scheme: form.color_scheme.value,
            ui_language: form.ui_language.value,
            image_quality: form.image_quality.value,
            experimental_features: form.experimental_features.checked
        };

        // Save to localStorage
        localStorage.setItem('userPreferences', JSON.stringify(settings));

        // Apply preferences immediately
        applyUserPreferences(settings);

        // Dispatch change event
        window.dispatchEvent(new Event('userPreferencesChanged'));

        // Optional: feedback
        // alert("Preferences saved!");
    });
});

// Function to save user preferences to localStorage
    // Immediately apply theme to prevent FOUC
    (function() {
        const savedSettings = localStorage.getItem('userPreferences');
        const settings = savedSettings ? JSON.parse(savedSettings) : {
            color_scheme: 'default',
            ui_language: 'en',
            image_quality: 'medium',
            experimental_features: false
        };
        
        // Apply color scheme immediately
        if (settings.color_scheme === 'dark' || 
            (settings.color_scheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        
        // Set other attributes
        document.documentElement.dataset.imageQuality = settings.image_quality;
        document.documentElement.lang = settings.ui_language;
    })();

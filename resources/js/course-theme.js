function showThemeInput() {
    const container = document.getElementById('new-theme-container');
    const input = document.getElementById('new-theme-input');
    container.classList.remove('hidden');
    input.focus();
}

function addTheme() {
    const input = document.getElementById('new-theme-input');
    const theme = input.value.trim();
    
    if (!theme) return;

    const selectedThemes = document.getElementById('selected-themes');
    const existingThemes = Array.from(selectedThemes.querySelectorAll('.theme-badge'))
                                .map(badge => badge.textContent.replace('×', '').trim());

    if (existingThemes.includes(theme)) {
        alert('Theme already added!');
        return;
    }

    // Create the badge element
    const badge = document.createElement('div');
    badge.className = 'theme-badge inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm mr-2';
    badge.innerHTML = `
        ${theme}
        <button type="button" onclick="removeTheme(this)" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
    `;

    // Insert the badge before the "New+" trigger
    const newTrigger = document.getElementById('new-theme-trigger');
    selectedThemes.insertBefore(badge, newTrigger);

    // Update the hidden input
    const hiddenInput = document.getElementById('theme-input');
    const themes = hiddenInput.value ? hiddenInput.value.split(',') : [];
    themes.push(theme);
    hiddenInput.value = themes.join(',');

    // Hide input and clear value
    document.getElementById('new-theme-container').classList.add('hidden');
    input.value = '';
}

function removeTheme(button) {
    const badge = button.closest('.theme-badge');
    const theme = badge.textContent.replace('×', '').trim();

    badge.remove();

    // Update the hidden input
    const hiddenInput = document.getElementById('theme-input');
    let themes = hiddenInput.value.split(',');
    themes = themes.filter(t => t.trim() !== theme);
    hiddenInput.value = themes.join(',');
}

// Make functions global
window.showThemeInput = showThemeInput;
window.addTheme = addTheme;
window.removeTheme = removeTheme;
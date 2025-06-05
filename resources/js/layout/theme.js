// Check for dark mode in local storage
const themeToggle = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");
const html = document.documentElement;

// Function to update the icon
function updateThemeIcon() {
    if (html.classList.contains("dark")) {
        themeIcon.textContent = "light_mode"; // Light mode icon
    } else {
        themeIcon.textContent = "dark_mode"; // Dark mode icon
    }
}

// Load stored theme preference
if (localStorage.getItem("theme") === "dark") {
    html.classList.add("dark");
    updateThemeIcon();
}

// Theme toggle event
themeToggle.addEventListener("click", () => {
    html.classList.toggle("dark");

    // Store the theme preference
    if (html.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }

    updateThemeIcon();
});
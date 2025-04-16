document.addEventListener("DOMContentLoaded", () => {
    const darkModeToggle = document.getElementById("darkModeToggle");
    const body = document.body;

    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
    const currentTheme = localStorage.getItem("theme");

    if (
        currentTheme === "dark" ||
        (!currentTheme && prefersDarkScheme.matches)
    ) {
        body.classList.add("dark-mode");
        darkModeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
    } else {
        darkModeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
    }

    darkModeToggle.addEventListener("click", () => {
        body.classList.toggle("dark-mode");
        const theme = body.classList.contains("dark-mode") ? "dark" : "light";
        localStorage.setItem("theme", theme);

        if (body.classList.contains("dark-mode")) {
            darkModeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
        } else {
            darkModeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
        }
    });
});

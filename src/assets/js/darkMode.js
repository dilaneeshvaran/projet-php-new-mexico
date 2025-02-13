document.addEventListener("DOMContentLoaded", () => {
  const darkModeToggle = document.getElementById("darkModeToggle");
  const themeIcon = darkModeToggle.querySelector(".theme-icon");
  const themeText = darkModeToggle.querySelector(".theme-text");

  const setTheme = (theme) => {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);

    themeIcon.alt = theme === "dark" ? "Light Mode" : "Dark Mode";
    themeText.textContent = theme === "dark" ? "Light Mode" : "Dark Mode";

    //change the icon image
    // themeIcon.src = theme === "dark" ? "/assets/images/lightmode.png" : "/assets/images/darkmode.png";
  };

  const initializeTheme = () => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
      setTheme(savedTheme);
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
      setTheme("dark");
    } else {
      setTheme("light");
    }
  };

  darkModeToggle.addEventListener("click", () => {
    const currentTheme = document.documentElement.getAttribute("data-theme");
    setTheme(currentTheme === "dark" ? "light" : "dark");
  });

  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (e) => {
      if (!localStorage.getItem("theme")) {
        setTheme(e.matches ? "dark" : "light");
      }
    });

  initializeTheme();
});

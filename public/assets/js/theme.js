const theme = () => {
  const html = document.querySelector("html");
  let currentMode = localStorage.getItem("theme");

    // If the theme is not set check the browser's preferred color scheme
  if (!currentMode) {
    if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
      currentMode = "dark";
    } else {
      currentMode = "light";
    }
  }

  if (currentMode === "dark") {
    html.classList.add("dark");
  } else if (currentMode === "light") {
    html.classList.remove("dark");
  }

  const themeController = document.querySelector(".theme-controller");
  themeController.addEventListener("click", function () {
    html.classList.toggle("dark");
    const currentMode = html.classList.contains("dark");

    if (currentMode) {
      localStorage.setItem("theme", "dark");
    } else {
      localStorage.setItem("theme", "light");
    }
  });
};
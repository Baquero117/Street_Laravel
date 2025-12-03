document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  const sidebar = document.getElementById("sidebar") || document.querySelector(".sidebar");
  const collapseBtn = document.getElementById("collapse");
  const darkModeToggle = document.getElementById("darkmode");


  const collapsedState = localStorage.getItem("sidebarCollapsed");
  if (collapsedState === "true") {
    sidebar.classList.add("collapsed");
    const icon = collapseBtn.querySelector("i");
    if (icon) {
      icon.classList.remove("fa-angle-double-left");
      icon.classList.add("fa-angle-double-right");
    }
  }

  
  const darkState = localStorage.getItem("darkmode");
  if (darkState === "enabled") {
    body.classList.add("darkmode");
    if (darkModeToggle) darkModeToggle.checked = true;
  }

  
  if (collapseBtn) {
    collapseBtn.addEventListener("click", function () {
      sidebar.classList.toggle("collapsed");
      const isCollapsed = sidebar.classList.contains("collapsed");
      localStorage.setItem("sidebarCollapsed", isCollapsed ? "true" : "false");

      
      const icon = collapseBtn.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-angle-double-left", !isCollapsed);
        icon.classList.toggle("fa-angle-double-right", isCollapsed);
      }
    });
  }

 
  if (darkModeToggle) {
    darkModeToggle.addEventListener("change", function () {
      if (this.checked) {
        body.classList.add("darkmode");
        localStorage.setItem("darkmode", "enabled");
      } else {
        body.classList.remove("darkmode");
        localStorage.setItem("darkmode", "disabled");
      }
    });
  }
});


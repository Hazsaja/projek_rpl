document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.querySelector(".menu-toggle-btn");
  const sidebar = document.querySelector(".sidebar");
  const menuIcon = document.querySelector("#menu-icon");

  const sidebarState = localStorage.getItem("sidebar-state");

  if(sidebarState === "collapsed"){
    sidebar.classList.add("collapsed");
  }

  toggleBtn.addEventListener("click", function () {
    sidebar.classList.toggle("collapsed");
    
    if (sidebar.classList.contains("collapsed")) {
      menuIcon.innerHTML = `
        <line x1="4" y1="6" x2="20" y2="6"></line>
        <line x1="4" y1="12" x2="20" y2="12"></line>
        <line x1="4" y1="18" x2="20" y2="18"></line>
      `;
      localStorage.setItem("sidebar-state", "collapsed");
    } else {
      menuIcon.innerHTML = `
        <path d="M18 6 6 18"></path>
        <path d="m6 6 12 12"></path>
      `;
      localStorage.setItem("sidebar-state", "expanded");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const closeBtn = document.querySelector(".close-btn");
  const infoAlert = document.querySelector(".info-alert");

  if (closeBtn && infoAlert) {
    closeBtn.addEventListener("click", function () {
      infoAlert.style.display = "none";
    });
  }
});

document.addEventListener("DOMContentLoaded", function() { 
    const toggleBtn = document.querySelector(".menu-toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    toggleBtn.addEventListener("click", function() {
        sidebar.classList.toggle("collapsed");
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const closeBtn = document.querySelector('.close-btn');
    const infoAlert = document.querySelector('.info-alert');

    if (closeBtn && infoAlert) {
        closeBtn.addEventListener('click', function() {
            infoAlert.style.display = 'none';
        });
    }
});
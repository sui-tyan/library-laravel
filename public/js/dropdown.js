sidebar;
sidebarBackdrop;

document
    .getElementById("toggleSidebarMobile")
    .addEventListener("click", function () {
        let sidebar = document.getElementById("sidebar");
        let backdrop = document.getElementById("sidebarBackdrop");

        sidebar.classList.toggle("hidden");
        backdrop.classList.toggle("hidden");
    });

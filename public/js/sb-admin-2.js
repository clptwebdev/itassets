(function ($) {
    "use strict"; // Start of use strict

    const body = document.querySelector("body");
    const sidebar = document.querySelector(".sidebar");
    const topButton = document.querySelector("#sidebarToggleTop");
    const sideButton = document.querySelector("#sidebarToggle");
    const sideTitles = document.querySelectorAll(".sidebar-title");
    const sideIcons = document.querySelectorAll(".sidebar-icon");
    const dropdowns = document.querySelectorAll(".sidebar .nav-item div");
    const closeButton = document.querySelector('#close_sidebar');
    const scrollButton = document.getElementById('scrollButton');

    const subMenu = document.querySelector('#subMenu');
    const subMenuLinks = document.querySelectorAll('#subMenu > a > i');
    const subMenuFormBtns = document.querySelectorAll('#subMenu > form > button > i');
    const subMenuBtns = document.querySelectorAll('#subMenu > button > i');

    if (window.innerWidth < 768) {
        subMenuLinks.forEach(item => {
            item.classList.remove('fa-sm');
            item.classList.add('fa-lg');
        })

        subMenuBtns.forEach(item => {
            item.classList.remove('fa-sm');
            item.classList.add('fa-lg');
        })

        subMenuFormBtns.forEach(item => {
            item.classList.remove('fa-sm');
            item.classList.add('fa-lg');
        })
    }


    // Toggle the side navigation when window is resized below 480px
    if (
        window.innerWidth < 768 &&
        sidebar.classList.contains("toggled") === false
    ) {
        body.classList.add("sidebar-toggled");
        sidebar.classList.add("toggled");
    }

    if (window.innerWidth > 768 && window.innerWidth < 992) {
        body.classList.remove("sidebar-toggled");
        sidebar.classList.remove("toggled");
        sideTitles.forEach((item) => {
            item.classList.add("d-none");
        });
        sideIcons.forEach((item) => {
            item.classList.add("fa-2x");
        });

    }

    //Two buttons should have seperate add eventlisteners as one should shrink the sidebar and only be available on small screens

    sideButton.addEventListener("click", function () {
        this.classList.toggle('toggled');
        sidebar.classList.toggle("sidebar-toggle");
        dropdowns.forEach((dd) => {
            dd.classList.toggle('dropdown-toggled');
        });
        if (window.innerWidth > 768) {
            sideTitles.forEach((item) => {
                item.classList.toggle("d-none");
            });
            sideIcons.forEach((item) => {
                item.classList.toggle("fa-2x");
            });
        }
    });

    //And other one should showing the items

    topButton.addEventListener("click", function () {
        body.classList.toggle("sidebar-toggled");
        sidebar.classList.toggle("toggled");
        if (sidebar.classList.contains("toggled")) {
            dropdowns.forEach((item) => {
                item.classList.add("collapse");
            });
        }
    });

    // Close any open menu accordions when window is resized below 768px
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768 && window.innerWidth < 992) {
            body.classList.remove("sidebar-toggled");
            sidebar.classList.remove("toggled");
            sideTitles.forEach((item) => {
                item.classList.add("d-none");
            });
            sideIcons.forEach((item) => {
                item.classList.add("fa-2x");
            });
        } else {
            sideTitles.forEach((item) => {
                item.classList.remove("d-none");
            });
            sideIcons.forEach((item) => {
                item.classList.remove("fa-2x");
            });
        }

        if (window.innerWidth <= 768) {
            body.classList.add("sidebar-toggled");
            sidebar.classList.add("toggled");
        }

        if (window.innerWidth > 992) {
            body.classList.remove("sidebar-toggled");
            sidebar.classList.remove("toggled");
        }
    });

    closeButton.addEventListener('click', function () {
        body.classList.toggle("sidebar-toggled");
        sidebar.classList.toggle("toggled");
    });
})(); // End of use strict

(function ($) {
    "use strict"; // Start of use strict

    const body = document.querySelector("body");
    const sidebar = document.querySelector(".sidebar");
    const topButton = document.querySelector("#sidebarToggleTop");
    const sideButton = document.querySelector("#sidebarToggle");
    const sideTitles = document.querySelectorAll(".sidebar-title");
    const sideIcons = document.querySelectorAll(".sidebar-icon");
    const dropdowns = document.querySelectorAll(".sidebar .nav-item div");

    // Toggle the side navigation when window is resized below 480px
    if (
        window.innerWidth < 768 &&
        sidebar.classList.contains("toggled") === false
    ) {
        body.classList.add("sidebar-toggled");
        sidebar.classList.add("toggled");
    }

    //Two buttons should have seperate add eventlisteners as one should shrink the sidebar and only be available on small screens

    sideButton.addEventListener("click", function () {
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
        if (window.innerWidth > 768 && window.innerWidth <= 992) {
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

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $("body.fixed-nav .sidebar").on(
        "mousewheel DOMMouseScroll wheel",
        function (e) {
            if ($(window).width() > 768) {
                var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;
                this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                e.preventDefault();
            }
        }
    );

    // Scroll to top button appear
    $(document).on("scroll", function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $(".scroll-to-top").fadeIn();
        } else {
            $(".scroll-to-top").fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on("click", "a.scroll-to-top", function (e) {
        var $anchor = $(this);
        $("html, body")
            .stop()
            .animate(
                {
                    scrollTop: $($anchor.attr("href")).offset().top,
                },
                1000,
                "easeInOutExpo"
            );
        e.preventDefault();
    });
})(jQuery); // End of use strict

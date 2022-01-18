(function ($) {
    "use strict"; // Start of use strict

    const body = document.querySelector("body");
    const sidebar = document.querySelector(".sidebar");
    const topButton = document.querySelector("#sidebarToggleTop");
    const sideButton = document.querySelector("#sidebarToggle");
    const sideTitles = document.querySelectorAll(".sidebar-title");
    const sideIcons = document.querySelectorAll(".sidebar-icon");

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
            $(".sidebar .collapse").collapse("hide");
        }
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() >= 768) {
            $("body").removeClass("sidebar-toggled");
            $(".sidebar").removeClass("toggled");
            $(".sidebar-title").removeClass("d-none");
            $(".sidebar-icon").removeClass("fa-2x");
        }

        if ($(window).width() > 480 && $(window).width() < 768) {
            $(".sidebar-title").addClass("d-none");
            $(".sidebar-icon").addClass("fa-2x");
            $(".sidebar .collapse").collapse("hide");
        }

        // Toggle the side navigation when window is resized below 480px
        if ($(window).width() <= 480 && !$(".sidebar").hasClass("toggled")) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
        }

        if ($(window).width() < 480) {
            $(".sidebar").addClass("toggled");
            $(".sidebar-title").addClass("d-none");
            $(".sidebar-icon").addClass("fa-2x");
            $(".sidebar .collapse").collapse("hide");
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

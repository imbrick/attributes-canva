/**
 * Enhanced JavaScript functionality for Attributes Canva theme
 */
(function ($) {
  "use strict";

  // Theme object
  const AttributesCanva = {
    init: function () {
      this.setupMobileMenu();
      this.setupSmoothScrolling();
      this.setupLazyLoading();
      this.setupSearchEnhancements();
      this.setupAccessibility();
      this.setupPerformanceOptimizations();
    },

    setupMobileMenu: function () {
      const menuToggle = $(".menu-toggle");
      const mainNav = $(".main-navigation");

      menuToggle.on("click", function (e) {
        e.preventDefault();
        const isExpanded = $(this).attr("aria-expanded") === "true";

        $(this).attr("aria-expanded", !isExpanded);
        mainNav.toggleClass("active");

        // Focus management
        if (!isExpanded) {
          mainNav.find("a").first().focus();
        }
      });

      // Close menu on escape key
      $(document).on("keydown", function (e) {
        if (e.key === "Escape" && mainNav.hasClass("active")) {
          mainNav.removeClass("active");
          menuToggle.attr("aria-expanded", "false").focus();
        }
      });
    },

    setupSmoothScrolling: function () {
      $('a[href*="#"]:not([href="#"])').on("click", function (e) {
        const target = $(this.hash);
        if (target.length) {
          e.preventDefault();
          $("html, body").animate(
            {
              scrollTop: target.offset().top - 100,
            },
            500
          );
        }
      });
    },

    setupLazyLoading: function () {
      if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.src = img.dataset.src;
              img.classList.remove("lazy");
              observer.unobserve(img);
            }
          });
        });

        $(".lazy").each(function () {
          imageObserver.observe(this);
        });
      }
    },

    setupSearchEnhancements: function () {
      const searchForm = $(".search-form");
      const searchField = $(".search-field");

      // Live search functionality
      let searchTimeout;
      searchField.on("input", function () {
        clearTimeout(searchTimeout);
        const query = $(this).val();

        if (query.length > 2) {
          searchTimeout = setTimeout(() => {
            AttributesCanva.performLiveSearch(query);
          }, 300);
        }
      });
    },

    performLiveSearch: function (query) {
      $.ajax({
        url: attributesEnhanced.ajax_url,
        type: "POST",
        data: {
          action: "attributes_live_search",
          nonce: attributesEnhanced.nonce,
          query: query,
        },
        success: function (response) {
          if (response.success) {
            // Display search results
            console.log("Search results:", response.data);
          }
        },
        error: function () {
          console.error("Search failed");
        },
      });
    },

    setupAccessibility: function () {
      // Skip link functionality
      $(".skip-link").on("click", function (e) {
        const target = $($(this).attr("href"));
        if (target.length) {
          target.focus();
        }
      });

      // Keyboard navigation for dropdowns
      $(".menu-item-has-children > a").on("keydown", function (e) {
        if (e.key === "ArrowDown") {
          e.preventDefault();
          $(this).siblings(".sub-menu").find("a").first().focus();
        }
      });
    },

    setupPerformanceOptimizations: function () {
      // Preload critical resources
      const criticalResources = [
        "/assets/css/style.css",
        "/assets/js/script.js",
      ];

      criticalResources.forEach((resource) => {
        const link = document.createElement("link");
        link.rel = "preload";
        link.href = attributesEnhanced.theme_url + resource;
        link.as = resource.endsWith(".css") ? "style" : "script";
        document.head.appendChild(link);
      });
    },
  };

  // Initialize when DOM is ready
  $(document).ready(function () {
    AttributesCanva.init();
  });

  // Expose to global scope
  window.AttributesCanva = AttributesCanva;
})(jQuery);

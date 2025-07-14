/**
 * Live preview functionality for Attributes Canva theme customizer
 */
(function ($) {
  "use strict";

  // Helper function to update CSS custom properties
  function updateCSSVariable(property, value) {
    document.documentElement.style.setProperty(property, value);
  }

  // Helper function to safely get elements
  function getElements(selector) {
    return document.querySelectorAll(selector);
  }

  // Live preview for colors
  wp.customize("attributes_canva_primary_color", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--primary-color", newval);
    });
  });

  wp.customize("attributes_canva_secondary_color", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--secondary-color", newval);
    });
  });

  wp.customize("attributes_canva_accent_color", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--accent-color", newval);
    });
  });

  wp.customize("attributes_canva_text_color", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--text-color", newval);
    });
  });

  wp.customize("attributes_canva_header_background", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--header-background", newval);
    });
  });

  wp.customize("attributes_canva_footer_background", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--footer-background", newval);
    });
  });

  wp.customize("attributes_canva_footer_text_color", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--footer-text-color", newval);
    });
  });

  // Live preview for typography
  wp.customize("attributes_canva_headings_font", function (value) {
    value.bind(function (newval) {
      const fontStacks = {
        "system-ui":
          '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        arial: "Arial, Helvetica, sans-serif",
        helvetica: '"Helvetica Neue", Helvetica, Arial, sans-serif',
        georgia: 'Georgia, "Times New Roman", Times, serif',
        Inter: '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
        Roboto: '"Roboto", Arial, sans-serif',
        "Open Sans": '"Open Sans", Arial, sans-serif',
        Lato: '"Lato", Arial, sans-serif',
        Montserrat: '"Montserrat", Arial, sans-serif',
        Poppins: '"Poppins", Arial, sans-serif',
      };

      const fontStack = fontStacks[newval] || fontStacks["Inter"];
      updateCSSVariable("--headings-font", fontStack);

      // Load Google Font if needed
      if (
        [
          "Inter",
          "Roboto",
          "Open Sans",
          "Lato",
          "Montserrat",
          "Poppins",
        ].includes(newval)
      ) {
        loadGoogleFont(newval);
      }
    });
  });

  wp.customize("attributes_canva_body_font", function (value) {
    value.bind(function (newval) {
      const fontStacks = {
        "system-ui":
          '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        arial: "Arial, Helvetica, sans-serif",
        helvetica: '"Helvetica Neue", Helvetica, Arial, sans-serif',
        georgia: 'Georgia, "Times New Roman", Times, serif',
        Inter: '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
        Roboto: '"Roboto", Arial, sans-serif',
        "Open Sans": '"Open Sans", Arial, sans-serif',
        Lato: '"Lato", Arial, sans-serif',
        Montserrat: '"Montserrat", Arial, sans-serif',
        Poppins: '"Poppins", Arial, sans-serif',
      };

      const fontStack = fontStacks[newval] || fontStacks["Inter"];
      updateCSSVariable("--body-font", fontStack);

      // Load Google Font if needed
      if (
        [
          "Inter",
          "Roboto",
          "Open Sans",
          "Lato",
          "Montserrat",
          "Poppins",
        ].includes(newval)
      ) {
        loadGoogleFont(newval);
      }
    });
  });

  wp.customize("attributes_canva_font_size", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--base-font-size", newval + "px");
    });
  });

  wp.customize("attributes_canva_line_height", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--line-height", newval);
    });
  });

  // Live preview for layout
  wp.customize("attributes_canva_container_width", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--container-width", newval + "px");
    });
  });

  wp.customize("attributes_canva_content_spacing", function (value) {
    value.bind(function (newval) {
      const spacingValues = {
        compact: "0.75",
        standard: "1",
        relaxed: "1.25",
        loose: "1.5",
      };
      const multiplier = spacingValues[newval] || "1";
      updateCSSVariable("--spacing-multiplier", multiplier);
    });
  });

  // Live preview for header layout
  wp.customize("attributes_canva_header_layout", function (value) {
    value.bind(function (newval) {
      const headerContainer = document.querySelector(".header-container");
      if (!headerContainer) return;

      // Remove existing layout classes
      headerContainer.classList.remove(
        "layout-1",
        "layout-2",
        "layout-3",
        "layout-4"
      );

      // Add new layout class
      headerContainer.classList.add(newval);

      // Apply specific styles
      switch (newval) {
        case "layout-2":
          headerContainer.style.flexDirection = "column";
          headerContainer.style.textAlign = "center";
          break;
        case "layout-3":
          headerContainer.style.flexDirection = "row-reverse";
          headerContainer.style.textAlign = "left";
          break;
        default:
          headerContainer.style.flexDirection = "row";
          headerContainer.style.textAlign = "left";
      }
    });
  });

  // Live preview for sticky header
  wp.customize("attributes_canva_sticky_header", function (value) {
    value.bind(function (newval) {
      const header = document.querySelector(".site-header");
      if (!header) return;

      if (newval) {
        header.style.position = "sticky";
        header.style.top = "0";
        header.style.zIndex = "999";
      } else {
        header.style.position = "relative";
        header.style.top = "auto";
        header.style.zIndex = "auto";
      }
    });
  });

  // Live preview for show/hide elements
  wp.customize("attributes_canva_header_search", function (value) {
    value.bind(function (newval) {
      const searchElement = document.querySelector(".header-search");
      if (searchElement) {
        searchElement.style.display = newval ? "block" : "none";
      }
    });
  });

  wp.customize("attributes_canva_header_cta_enabled", function (value) {
    value.bind(function (newval) {
      const ctaElement = document.querySelector(".header-cta");
      if (ctaElement) {
        ctaElement.style.display = newval ? "block" : "none";
      }
    });
  });

  wp.customize("attributes_canva_show_featured_images", function (value) {
    value.bind(function (newval) {
      const featuredImages = getElements(".post-thumbnail, .page-thumbnail");
      featuredImages.forEach(function (img) {
        img.style.display = newval ? "block" : "none";
      });
    });
  });

  wp.customize("attributes_canva_show_excerpts", function (value) {
    value.bind(function (newval) {
      const excerpts = getElements(".entry-summary");
      excerpts.forEach(function (excerpt) {
        excerpt.style.display = newval ? "block" : "none";
      });
    });
  });

  wp.customize("attributes_canva_show_reading_time", function (value) {
    value.bind(function (newval) {
      const readingTimes = getElements(".reading-time, .reading-time-wrapper");
      readingTimes.forEach(function (time) {
        time.style.display = newval ? "inline" : "none";
      });
    });
  });

  wp.customize("attributes_canva_back_to_top", function (value) {
    value.bind(function (newval) {
      const backToTop = document.querySelector(".back-to-top");
      if (backToTop) {
        backToTop.style.display = newval ? "block" : "none";
      }
    });
  });

  wp.customize("attributes_canva_dark_mode_toggle", function (value) {
    value.bind(function (newval) {
      const darkModeToggle = document.querySelector(".dark-mode-toggle");
      if (darkModeToggle) {
        darkModeToggle.style.display = newval ? "block" : "none";
      }
    });
  });

  wp.customize("attributes_canva_smooth_scrolling", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.scrollBehavior = newval
        ? "smooth"
        : "auto";
    });
  });

  // Function to load Google Fonts dynamically
  function loadGoogleFont(fontFamily) {
    const fontName = fontFamily.replace(" ", "+");
    const fontUrl = `https://fonts.googleapis.com/css2?family=${fontName}:wght@300;400;500;600;700&display=swap`;

    // Check if font is already loaded
    if (document.querySelector(`link[href*="${fontName}"]`)) {
      return;
    }

    // Create and append font link
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = fontUrl;
    link.id = "customizer-font-" + fontName.toLowerCase();
    document.head.appendChild(link);
  }

  // Live preview for text content updates
  wp.customize("attributes_canva_header_cta_text", function (value) {
    value.bind(function (newval) {
      const ctaButton = document.querySelector(
        ".header-cta a, .header-cta .btn"
      );
      if (ctaButton) {
        ctaButton.textContent = newval;
      }
    });
  });

  wp.customize("attributes_canva_footer_text", function (value) {
    value.bind(function (newval) {
      const footerText = document.querySelector(".custom-footer-text");
      if (footerText) {
        footerText.innerHTML = newval;
      } else if (newval) {
        // Create footer text element if it doesn't exist
        const siteInfo = document.querySelector(".site-info .container");
        if (siteInfo) {
          const footerDiv = document.createElement("div");
          footerDiv.className = "custom-footer-text";
          footerDiv.innerHTML = newval;
          siteInfo.appendChild(footerDiv);
        }
      }
    });
  });

  // Live preview for border radius
  wp.customize("attributes_canva_border_radius", function (value) {
    value.bind(function (newval) {
      updateCSSVariable("--border-radius", newval + "px");
    });
  });

  // Live preview for shadow intensity
  wp.customize("attributes_canva_shadow_intensity", function (value) {
    value.bind(function (newval) {
      const intensityMap = {
        none: "0",
        subtle: "0.05",
        medium: "0.1",
        strong: "0.2",
      };
      const alpha = intensityMap[newval] || "0.1";
      updateCSSVariable("--shadow-alpha", alpha);
    });
  });

  // Live preview for animation speed
  wp.customize("attributes_canva_animation_speed", function (value) {
    value.bind(function (newval) {
      const speedMap = {
        fast: "0.15s",
        normal: "0.3s",
        slow: "0.5s",
      };
      const speed = speedMap[newval] || "0.3s";
      updateCSSVariable("--animation-speed", speed);
    });
  });

  // Blog layout live preview
  wp.customize("attributes_canva_blog_layout", function (value) {
    value.bind(function (newval) {
      const postGrid = document.querySelector(".post-grid");
      if (!postGrid) return;

      // Remove existing layout classes
      postGrid.classList.remove("layout-list", "layout-grid", "layout-masonry");

      // Add new layout class
      postGrid.classList.add("layout-" + newval);

      // Apply specific grid styles
      switch (newval) {
        case "list":
          postGrid.style.gridTemplateColumns = "1fr";
          postGrid.style.gap = "2rem";
          break;
        case "grid":
          postGrid.style.gridTemplateColumns =
            "repeat(auto-fit, minmax(300px, 1fr))";
          postGrid.style.gap = "2rem";
          break;
        case "masonry":
          postGrid.style.gridTemplateColumns =
            "repeat(auto-fill, minmax(280px, 1fr))";
          postGrid.style.gap = "1.5rem";
          break;
      }
    });
  });

  // Archive columns live preview
  wp.customize("attributes_canva_archive_columns", function (value) {
    value.bind(function (newval) {
      const postGrid = document.querySelector(".post-grid");
      if (postGrid) {
        postGrid.style.gridTemplateColumns = `repeat(${newval}, 1fr)`;
      }
    });
  });
})(jQuery);

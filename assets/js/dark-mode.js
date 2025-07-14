/**
 * Enhanced Dark Mode Toggle with smooth transitions and system preference detection
 */
(function ($) {
  "use strict";

  class DarkModeToggle {
    constructor() {
      this.init();
    }

    init() {
      this.createToggleButton();
      this.bindEvents();
      this.checkSystemPreference();
      this.loadUserPreference();
    }

    createToggleButton() {
      // Create the toggle button
      const toggleButton = $(`
                <button class="dark-mode-toggle" 
                        aria-label="Toggle dark mode" 
                        title="Toggle dark mode">
                    <span class="toggle-icon">
                        <svg class="sun-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 17.5c3.04 0 5.5-2.46 5.5-5.5S15.04 6.5 12 6.5 6.5 8.96 6.5 12s2.46 5.5 5.5 5.5zM12 5c.28 0 .5-.22.5-.5V2c0-.28-.22-.5-.5-.5s-.5.22-.5.5v2.5c0 .28.22.5.5.5zm0 14c-.28 0-.5.22-.5.5V22c0 .28.22.5.5.5s.5-.22.5-.5v-2.5c0-.28-.22-.5-.5-.5zM5.5 12c0-.28-.22-.5-.5-.5H2c-.28 0-.5.22-.5.5s.22.5.5.5h2.5c.28 0 .5-.22.5-.5zm17 0c0-.28-.22-.5-.5-.5h-2.5c-.28 0-.5.22-.5.5s.22.5.5.5H22c.28 0 .5-.22.5-.5zM6.34 6.34c.2-.2.2-.51 0-.71L4.93 4.22c-.2-.2-.51-.2-.71 0-.2.2-.2.51 0 .71l1.41 1.41c.2.2.51.2.71 0zm12.02 12.02c-.2-.2-.51-.2-.71 0-.2.2-.2.51 0 .71l1.41 1.41c.2.2.51.2.71 0 .2-.2.2-.51 0-.71l-1.41-1.41zm1.41-12.02c.2-.2.2-.51 0-.71-.2-.2-.51-.2-.71 0l-1.41 1.41c-.2.2-.2.51 0 .71.2.2.51.2.71 0l1.41-1.41zM6.34 17.66c.2.2.51.2.71 0 .2-.2.2-.51 0-.71L5.64 15.54c-.2-.2-.51-.2-.71 0-.2.2-.2.51 0 .71l1.41 1.41z"/>
                        </svg>
                        <svg class="moon-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="display: none;">
                            <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                    </span>
                </button>
            `);

      // Add the button to the page
      $("body").append(toggleButton);
      this.$toggleButton = $(".dark-mode-toggle");
    }

    bindEvents() {
      // Toggle button click event
      this.$toggleButton.on("click", (e) => {
        e.preventDefault();
        this.toggleDarkMode();
      });

      // Keyboard support
      this.$toggleButton.on("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          this.toggleDarkMode();
        }
      });

      // Listen for system theme changes
      if (window.matchMedia) {
        const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
        mediaQuery.addEventListener("change", (e) => {
          if (!this.hasUserPreference()) {
            this.setDarkMode(e.matches);
          }
        });
      }

      // Auto-hide toggle on scroll (optional)
      let scrollTimeout;
      $(window).on("scroll", () => {
        this.$toggleButton.addClass("scrolling");
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
          this.$toggleButton.removeClass("scrolling");
        }, 150);
      });
    }

    checkSystemPreference() {
      if (
        window.matchMedia &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
      ) {
        this.systemPrefersDark = true;
      }
    }

    loadUserPreference() {
      const savedPreference = localStorage.getItem("darkMode");

      if (savedPreference !== null) {
        // User has a saved preference
        this.setDarkMode(savedPreference === "true");
      } else if (this.systemPrefersDark) {
        // No saved preference, but system prefers dark
        this.setDarkMode(true);
      }
    }

    hasUserPreference() {
      return localStorage.getItem("darkMode") !== null;
    }

    toggleDarkMode() {
      const isDark = $("body").hasClass("dark-mode");
      this.setDarkMode(!isDark);
    }

    setDarkMode(isDark) {
      const $body = $("body");

      if (isDark) {
        $body.addClass("dark-mode");
        this.updateToggleIcon(true);
        this.$toggleButton.attr("aria-label", "Switch to light mode");
        this.$toggleButton.attr("title", "Switch to light mode");
      } else {
        $body.removeClass("dark-mode");
        this.updateToggleIcon(false);
        this.$toggleButton.attr("aria-label", "Switch to dark mode");
        this.$toggleButton.attr("title", "Switch to dark mode");
      }

      // Save preference
      localStorage.setItem("darkMode", isDark);

      // Trigger custom event
      $(document).trigger("darkModeToggled", [isDark]);

      // Add transition class for smooth animation
      $body.addClass("theme-transitioning");
      setTimeout(() => {
        $body.removeClass("theme-transitioning");
      }, 300);
    }

    updateToggleIcon(isDark) {
      const $sunIcon = this.$toggleButton.find(".sun-icon");
      const $moonIcon = this.$toggleButton.find(".moon-icon");

      if (isDark) {
        $sunIcon.hide();
        $moonIcon.show();
      } else {
        $sunIcon.show();
        $moonIcon.hide();
      }
    }
  }

  // Initialize when DOM is ready
  $(document).ready(() => {
    new DarkModeToggle();
  });

  // Add CSS for smooth transitions and button styling
  const darkModeStyles = `
        <style>
        .dark-mode-toggle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dark-mode-toggle.scrolling {
            opacity: 0.7;
            transform: translateY(-2px) scale(0.95);
        }
        
        .dark-mode-toggle:active {
            transform: translateY(-2px) scale(0.95);
        }
        
        .dark-mode-toggle .toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }
        
        .dark-mode-toggle:hover .toggle-icon {
            transform: rotate(15deg);
        }
        
        body.theme-transitioning * {
            transition: background-color 0.3s ease,
                       border-color 0.3s ease,
                       color 0.3s ease,
                       box-shadow 0.3s ease !important;
        }
        
        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            .dark-mode-toggle,
            .dark-mode-toggle .toggle-icon,
            body.theme-transitioning * {
                transition: none !important;
            }
        }
        
        /* Hide toggle on print */
        @media print {
            .dark-mode-toggle {
                display: none !important;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dark-mode-toggle {
                bottom: 1rem;
                right: 1rem;
                width: 44px;
                height: 44px;
                font-size: 1.1rem;
            }
        }
        </style>
    `;

  $("head").append(darkModeStyles);
})(jQuery);

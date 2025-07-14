/**
 * Enhanced controls for Attributes Canva theme customizer
 */
(function ($) {
  "use strict";

  wp.customize.bind("ready", function () {
    // Enhanced color picker with suggested colors
    wp.customize.control.each(function (control) {
      if (control.setting && control.setting.id.indexOf("_color") !== -1) {
        addColorSuggestions(control);
      }
    });

    // Font preview functionality
    addFontPreviews();

    // Conditional controls
    setupConditionalControls();

    // Reset to defaults functionality
    addResetButtons();

    // Import/Export functionality
    addImportExport();

    // Live typography preview
    addTypographyPreview();

    // Device preview helpers
    addDevicePreviewHelpers();
  });

  // Add color suggestions to color controls
  function addColorSuggestions(control) {
    const suggestedColors = [
      "#3182ce",
      "#e53e3e",
      "#38a169",
      "#d69e2e",
      "#805ad5",
      "#dd6b20",
      "#319795",
      "#e91e63",
      "#9c27b0",
      "#673ab7",
      "#2196f3",
      "#00bcd4",
      "#009688",
      "#4caf50",
      "#8bc34a",
      "#cddc39",
      "#ffeb3b",
      "#ffc107",
      "#ff9800",
      "#ff5722",
    ];

    if (control.container && control.container.find) {
      const colorControl = control.container.find(".wp-color-picker");
      if (colorControl.length) {
        const suggestions = $('<div class="color-suggestions">');

        suggestedColors.forEach(function (color) {
          const colorBtn = $('<button type="button" class="color-suggestion">')
            .css("background-color", color)
            .attr("data-color", color)
            .attr("title", color);

          colorBtn.on("click", function (e) {
            e.preventDefault();
            colorControl.wpColorPicker("color", color);
          });

          suggestions.append(colorBtn);
        });

        colorControl.after(suggestions);
      }
    }
  }

  // Add font previews
  function addFontPreviews() {
    const fontControls = [
      "attributes_canva_headings_font",
      "attributes_canva_body_font",
    ];

    fontControls.forEach(function (controlId) {
      const control = wp.customize.control(controlId);
      if (control) {
        const select = control.container.find("select");
        if (select.length) {
          select.after(
            '<div class="font-preview">The quick brown fox jumps over the lazy dog</div>'
          );

          const preview = control.container.find(".font-preview");

          select.on("change", function () {
            const fontFamily = $(this).val();
            updateFontPreview(preview, fontFamily);
          });

          // Initial preview
          updateFontPreview(preview, select.val());
        }
      }
    });
  }

  function updateFontPreview(preview, fontFamily) {
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

    const fontStack = fontStacks[fontFamily] || fontStacks["Inter"];
    preview.css("font-family", fontStack);

    // Load Google Font if needed
    if (
      [
        "Inter",
        "Roboto",
        "Open Sans",
        "Lato",
        "Montserrat",
        "Poppins",
      ].includes(fontFamily)
    ) {
      loadGoogleFontInCustomizer(fontFamily);
    }
  }

  function loadGoogleFontInCustomizer(fontFamily) {
    const fontName = fontFamily.replace(" ", "+");
    const fontUrl = `https://fonts.googleapis.com/css2?family=${fontName}:wght@300;400;500;600;700&display=swap`;

    if (!$(`link[href*="${fontName}"]`).length) {
      $("head").append(`<link rel="stylesheet" href="${fontUrl}">`);
    }
  }

  // Setup conditional controls
  function setupConditionalControls() {
    // Show/hide CTA button controls
    wp.customize("attributes_canva_header_cta_enabled", function (setting) {
      const toggleControls = function (enabled) {
        const ctaControls = [
          "attributes_canva_header_cta_text",
          "attributes_canva_header_cta_url",
        ];

        ctaControls.forEach(function (controlId) {
          const control = wp.customize.control(controlId);
          if (control) {
            control.container.toggle(enabled);
          }
        });
      };

      toggleControls(setting.get());
      setting.bind(toggleControls);
    });

    // Show/hide excerpt length when excerpts are enabled
    wp.customize("attributes_canva_show_excerpts", function (setting) {
      const toggleExcerptLength = function (enabled) {
        const control = wp.customize.control("attributes_canva_excerpt_length");
        if (control) {
          control.container.toggle(enabled);
        }
      };

      toggleExcerptLength(setting.get());
      setting.bind(toggleExcerptLength);
    });

    // Show/hide dark mode controls
    wp.customize("attributes_canva_enable_dark_mode", function (setting) {
      const toggleDarkModeControls = function (enabled) {
        const darkModeControls = [
          "attributes_canva_dark_mode_toggle",
          "attributes_canva_dark_mode_auto",
        ];

        darkModeControls.forEach(function (controlId) {
          const control = wp.customize.control(controlId);
          if (control) {
            control.container.toggle(enabled);
          }
        });
      };

      toggleDarkModeControls(setting.get());
      setting.bind(toggleDarkModeControls);
    });
  }

  // Add reset buttons to sections
  function addResetButtons() {
    const sectionsWithReset = [
      "attributes_canva_colors",
      "attributes_canva_typography",
      "attributes_canva_layout",
    ];

    sectionsWithReset.forEach(function (sectionId) {
      const section = wp.customize.section(sectionId);
      if (section) {
        section.container
          .find(".customize-section-title")
          .append(
            '<button type="button" class="reset-section-btn">Reset to Defaults</button>'
          );

        section.container.on("click", ".reset-section-btn", function (e) {
          e.preventDefault();
          resetSectionToDefaults(sectionId);
        });
      }
    });
  }

  function resetSectionToDefaults(sectionId) {
    const defaults = {
      attributes_canva_colors: {
        attributes_canva_primary_color: "#3182ce",
        attributes_canva_secondary_color: "#64748b",
        attributes_canva_accent_color: "#e53e3e",
      },
      attributes_canva_typography: {
        attributes_canva_headings_font: "Inter",
        attributes_canva_body_font: "Inter",
        attributes_canva_font_size: "16",
      },
      attributes_canva_layout: {
        attributes_canva_container_width: "1200",
        attributes_canva_content_spacing: "standard",
      },
    };

    const sectionDefaults = defaults[sectionId];
    if (sectionDefaults) {
      Object.keys(sectionDefaults).forEach(function (settingId) {
        const setting = wp.customize(settingId);
        if (setting) {
          setting.set(sectionDefaults[settingId]);
        }
      });
    }
  }

  // Add import/export functionality
  function addImportExport() {
    wp.customize.section("attributes_canva_colors").container.append(`
            <div class="import-export-controls">
                <button type="button" class="button export-settings">Export Settings</button>
                <button type="button" class="button import-settings">Import Settings</button>
                <input type="file" class="import-file" accept=".json" style="display: none;">
            </div>
        `);

    // Export functionality
    $(document).on("click", ".export-settings", function () {
      const settings = {};
      wp.customize.each(function (setting) {
        if (setting.id.indexOf("attributes_canva_") === 0) {
          settings[setting.id] = setting.get();
        }
      });

      const dataStr = JSON.stringify(settings, null, 2);
      const dataBlob = new Blob([dataStr], { type: "application/json" });
      const url = URL.createObjectURL(dataBlob);

      const link = document.createElement("a");
      link.href = url;
      link.download = "attributes-canva-settings.json";
      link.click();
    });

    // Import functionality
    $(document).on("click", ".import-settings", function () {
      $(".import-file").click();
    });

    $(document).on("change", ".import-file", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          try {
            const settings = JSON.parse(e.target.result);
            Object.keys(settings).forEach(function (settingId) {
              const setting = wp.customize(settingId);
              if (setting) {
                setting.set(settings[settingId]);
              }
            });
            alert("Settings imported successfully!");
          } catch (error) {
            alert("Error importing settings. Please check the file format.");
          }
        };
        reader.readAsText(file);
      }
    });
  }

  // Add typography preview
  function addTypographyPreview() {
    wp.customize.section("attributes_canva_typography").container.append(`
            <div class="typography-preview">
                <h3>Typography Preview</h3>
                <div class="preview-content">
                    <h1>Heading 1</h1>
                    <h2>Heading 2</h2>
                    <h3>Heading 3</h3>
                    <p>This is a paragraph of body text to demonstrate how your typography choices will look on your website. It includes multiple sentences to give you a better sense of readability and spacing.</p>
                </div>
            </div>
        `);

    // Update preview when font settings change
    const typographySettings = [
      "attributes_canva_headings_font",
      "attributes_canva_body_font",
      "attributes_canva_font_size",
    ];

    typographySettings.forEach(function (settingId) {
      wp.customize(settingId, function (setting) {
        setting.bind(updateTypographyPreview);
      });
    });

    function updateTypographyPreview() {
      const headingsFont = wp.customize("attributes_canva_headings_font").get();
      const bodyFont = wp.customize("attributes_canva_body_font").get();
      const fontSize = wp.customize("attributes_canva_font_size").get();

      const preview = $(".typography-preview .preview-content");
      preview.find("h1, h2, h3").css("font-family", getFontStack(headingsFont));
      preview.find("p").css({
        "font-family": getFontStack(bodyFont),
        "font-size": fontSize + "px",
      });
    }

    function getFontStack(fontFamily) {
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
      return fontStacks[fontFamily] || fontStacks["Inter"];
    }
  }

  // Add device preview helpers
  function addDevicePreviewHelpers() {
    $(".wp-full-overlay-footer .devices button").each(function () {
      const device = $(this).data("device");
      $(this).on("click", function () {
        // Add device-specific customizer tips
        showDeviceSpecificTips(device);
      });
    });
  }

  function showDeviceSpecificTips(device) {
    const tips = {
      desktop: "Perfect for testing layouts, typography, and overall design.",
      tablet: "Check navigation, content spacing, and image scaling.",
      mobile: "Focus on touch targets, readability, and mobile menu.",
    };

    if (tips[device]) {
      // Remove existing tips
      $(".device-tip").remove();

      // Add new tip
      $(".wp-full-overlay-footer").append(
        `<div class="device-tip">${tips[device]}</div>`
      );

      setTimeout(() => {
        $(".device-tip").fadeOut(500, function () {
          $(this).remove();
        });
      }, 3000);
    }
  }
})(jQuery);

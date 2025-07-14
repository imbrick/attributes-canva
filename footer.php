<?php

/**
 * The template for displaying the footer.
 * Enhanced for clean, professional design
 *
 * @package Attribute Canva
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
</main><!-- #main-content -->

<footer id="site-footer" class="site-footer" role="contentinfo" aria-label="<?php esc_attr_e('Site Footer', 'attribute-canva'); ?>">

    <!-- Footer Widgets -->
    <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
        <div class="footer-widgets">
            <?php for ($i = 1; $i <= 3; $i++) : ?>
                <?php if (is_active_sidebar("footer-$i")) : ?>
                    <div class="footer-widget-area footer-widget-<?php echo $i; ?>">
                        <?php dynamic_sidebar("footer-$i"); ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div><!-- .footer-widgets -->
    <?php endif; ?>

    <!-- Footer Navigation Menu -->
    <?php if (has_nav_menu('footer')) : ?>
        <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'attribute-canva'); ?>">
            <div class="container">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                    'container'      => false,
                ]);
                ?>
            </div>
        </nav><!-- .footer-navigation -->
    <?php endif; ?>

    <!-- Back to Top Button -->
    <div class="back-to-top">
        <button
            id="back-to-top-btn"
            class="btn btn-secondary btn-small"
            aria-label="<?php esc_attr_e('Back to top', 'attribute-canva'); ?>"
            style="display: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
            </svg>
            <?php esc_html_e('Top', 'attribute-canva'); ?>
        </button>
    </div><!-- .back-to-top -->

    <!-- Site Info -->
    <div class="site-info">
        <div class="container">
            <div class="site-info-content">
                <div class="copyright">
                    <p>
                        &copy; <?php echo date_i18n('Y'); ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php esc_html_e('All rights reserved.', 'attribute-canva'); ?>
                    </p>
                </div>

                <div class="powered-by">
                    <p>
                        <?php
                        printf(
                            /* translators: %s: WordPress link. */
                            esc_html__('Powered by %s', 'attribute-canva'),
                            '<a href="https://wordpress.org/" target="_blank" rel="noopener noreferrer">WordPress</a>'
                        );
                        ?>
                        <span class="sep"> | </span>
                        <?php
                        printf(
                            /* translators: %s: Theme name */
                            esc_html__('Theme: %s', 'attribute-canva'),
                            '<a href="https://attributeswp.com/" target="_blank" rel="noopener noreferrer">Attributes Canva</a>'
                        );
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div><!-- .site-info -->
</footer><!-- #site-footer -->

<?php wp_footer(); ?>

<!-- Enhanced JavaScript for better UX -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back to top functionality
        const backToTopBtn = document.getElementById('back-to-top-btn');

        if (backToTopBtn) {
            // Show/hide back to top button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.display = 'flex';
                    backToTopBtn.style.opacity = '1';
                } else {
                    backToTopBtn.style.opacity = '0';
                    setTimeout(() => {
                        if (window.pageYOffset <= 300) {
                            backToTopBtn.style.display = 'none';
                        }
                    }, 200);
                }
            });

            // Smooth scroll to top
            backToTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Enhanced mobile menu functionality
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.querySelector('.main-navigation');

        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

                menuToggle.setAttribute('aria-expanded', !isExpanded);
                mainNav.classList.toggle('active');

                // Add animation class
                if (!isExpanded) {
                    mainNav.style.display = 'block';
                    setTimeout(() => mainNav.classList.add('fade-in'), 10);
                } else {
                    mainNav.classList.remove('fade-in');
                    setTimeout(() => {
                        if (!mainNav.classList.contains('active')) {
                            mainNav.style.display = 'none';
                        }
                    }, 200);
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!menuToggle.contains(e.target) && !mainNav.contains(e.target)) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    mainNav.classList.remove('active', 'fade-in');
                    setTimeout(() => {
                        if (!mainNav.classList.contains('active')) {
                            mainNav.style.display = 'none';
                        }
                    }, 200);
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mainNav.classList.contains('active')) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    mainNav.classList.remove('active', 'fade-in');
                    menuToggle.focus();
                }
            });
        }

        // Enhanced search form
        const searchInputs = document.querySelectorAll('input[type="search"]');
        searchInputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('search-focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('search-focused');
            });
        });

        // Add loading states to buttons
        const buttons = document.querySelectorAll('button[type="submit"], input[type="submit"]');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Loading...';
                this.disabled = true;

                // Re-enable after form submission or timeout
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });
    });
</script>

</body>

</html>
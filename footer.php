
<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #main-content div and all content after.
 *
 * @package Attribute Canva
 */
?>
</main><!-- #main-content -->

<footer id="site-footer" class="site-footer" role="contentinfo" aria-label="Site Footer">
    <?php get_template_part('template-parts/footer/footer-widget'); ?>

    <!-- Footer Navigation Menu -->
    <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'attribute-canva'); ?>">
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'menu_class'     => 'footer-menu',
            'depth'          => 1, // Prevent dropdowns
            'fallback_cb'    => false, // No fallback to pages if no menu is set
        ]);
        ?>
    </nav><!-- .footer-navigation -->

    <!-- Back to Top Button -->
    <div class="back-to-top">
        <a href="#site-header"><?php esc_html_e('Back to Top', 'attribute-canva'); ?></a>
    </div><!-- .back-to-top -->

    <!-- Site Info -->
    <div class="site-info">
        <p>
            <?php
            printf(
                /* translators: %s: WordPress link. */
                esc_html__('Proudly powered by %s.', 'attribute-canva'),
                '<a href="https://wordpress.org/" target="_blank" rel="noopener noreferrer">WordPress</a>'
            );
            ?>
        </p>
        <p>
            &copy; <?php echo date_i18n('Y'); ?> 
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php bloginfo('name'); ?>
            </a>
            <?php esc_html_e('All rights reserved.', 'attribute-canva'); ?>
        </p>
    </div><!-- .site-info -->
</footer><!-- #site-footer -->

<?php wp_footer(); ?>
</body>
</html>

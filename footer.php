<footer id="site-footer" class="site-footer" role="contentinfo" aria-label="Site Footer">
    <div class="footer-widgets">
        <?php for ($i = 1; $i <= 3; $i++) : ?>
            <?php if (is_active_sidebar("footer-$i")) : ?>
                <div class="footer-widget footer-widget-<?php echo $i; ?>">
                    <?php dynamic_sidebar("footer-$i"); ?>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <div class="container">
        <nav class="footer-navigation" role="navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_class'     => 'footer-menu',
                'depth'          => 1,
            ));
            ?>
        </nav>
        <p>&copy; <?php echo date('Y'); ?> Attributes. All rights reserved.</p>
        <a href="#top" class="back-to-top"><?php _e('Back to Top', 'attributes-canva'); ?></a>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>

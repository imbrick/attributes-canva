<footer id="site-footer" class="site-footer" role="contentinfo">
    <?php if (is_active_sidebar('footer-1')) : ?>
        <div class="footer-widgets">
            <?php dynamic_sidebar('footer-1'); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Attribute. All rights reserved.</p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
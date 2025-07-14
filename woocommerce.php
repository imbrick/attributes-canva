// Create woocommerce.php
<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="woocommerce-container">
    <?php woocommerce_content(); ?>
</div>

<?php get_footer('shop'); ?>
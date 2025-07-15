<?php

/**
 * The main template file for WooCommerce.
 *
 * This file is used to display the main content of the WooCommerce shop page.
 *
 * @package WordPress
 * @subpackage WooCommerce
 * @since 1.1.0
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="woocommerce-container">
    <?php woocommerce_content(); ?>
</div>

<?php get_footer('shop'); ?>
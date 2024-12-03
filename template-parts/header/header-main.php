<?php
/**
 * Template part for displaying the main site header.
 *
 * @package Attribute Canva
 */
?>

<div class="header-left">
    <!-- Site Branding -->
    <div class="site-branding">
        <?php if (has_custom_logo()) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php else : ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
            </h1>
        <?php endif; ?>

        <?php if (display_header_text()) : ?>
            <p class="site-description"><?php bloginfo('description'); ?></p>
        <?php endif; ?>
    </div><!-- .site-branding -->

    <!-- Navigation Menu -->
    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Main Menu', 'attribute-canva'); ?>">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="menu-icon"></span>
            <?php esc_html_e('Menu', 'attribute-canva'); ?>
        </button>
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'container'      => 'ul',
            'fallback_cb'    => 'wp_page_menu',
        ]);
        ?>
    </nav><!-- #site-navigation -->
</div><!-- .header-main -->
<div class="header-right">
    <!-- Search Bar -->
    <div class="header-search">
        <?php get_search_form(); ?>
    </div><!-- .header-search -->

    <!-- Optional Call-to-Action Button -->
    <div class="header-cta">
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="cta-button">
            <?php esc_html_e('Get in Touch', 'attribute-canva'); ?>
        </a>
    </div><!-- .header-cta -->
</div><!-- .header-main -->
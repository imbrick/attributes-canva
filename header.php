<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header id="site-header" class="site-header <?php echo is_front_page() ? 'home-header' : 'inner-header'; ?>" role="banner">
        <div class="container">
            <h1 class="site-title">
                <?php 
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else { 
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                <?php } ?>
            </h1>
            <p class="site-description"><?php bloginfo('description'); ?></p>
        </div>
        <nav class="main-navigation" role="navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
            ));
            ?>
        </nav>
    </header>
    <a class="skip-link screen-reader-text" href="#main">Skip to content</a>

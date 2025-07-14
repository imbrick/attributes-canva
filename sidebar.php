<?php

/**
 * Security check - prevent direct access
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_active_sidebar('sidebar-1')) : ?>
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </aside>
<?php endif; ?>
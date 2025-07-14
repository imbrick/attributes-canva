<?php

/**
 * Security check - prevent direct access
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="footer-widgets">
    <?php for ($i = 1; $i <= 3; $i++): ?>
        <?php if (is_active_sidebar("footer-$i")): ?>
            <div class="footer-widget footer-widget-<?php echo $i; ?>">
                <?php dynamic_sidebar("footer-$i"); ?>
            </div>
        <?php endif; ?>
    <?php endfor; ?>
</div><!-- .footer-widgets -->
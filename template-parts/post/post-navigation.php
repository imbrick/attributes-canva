<?php

/**
 * Template part for displaying post navigation.
 * Enhanced with proper security and accessibility
 *
 * @package Attribute Canva
 */

// Security check - prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Only show navigation on single posts
if (!is_single()) {
    return;
}

$prev_post = get_previous_post();
$next_post = get_next_post();

// Don't display anything if there are no posts to navigate
if (!$prev_post && !$next_post) {
    return;
}
?>

<nav class="post-navigation" aria-label="<?php esc_attr_e('Post Navigation', 'attribute-canva'); ?>">
    <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'attribute-canva'); ?></h2>

    <div class="nav-links">
        <?php if ($prev_post) : ?>
            <div class="nav-previous">
                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" rel="prev" class="nav-link">
                    <div class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-subtitle"><?php esc_html_e('Previous Post', 'attribute-canva'); ?></span>
                        <span class="nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>

                        <?php if (has_post_thumbnail($prev_post)) : ?>
                            <div class="nav-thumbnail">
                                <?php echo get_the_post_thumbnail($prev_post, 'thumbnail', [
                                    'alt' => esc_attr(get_the_title($prev_post)),
                                    'loading' => 'lazy'
                                ]); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($next_post) : ?>
            <div class="nav-next">
                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" rel="next" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-subtitle"><?php esc_html_e('Next Post', 'attribute-canva'); ?></span>
                        <span class="nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>

                        <?php if (has_post_thumbnail($next_post)) : ?>
                            <div class="nav-thumbnail">
                                <?php echo get_the_post_thumbnail($next_post, 'thumbnail', [
                                    'alt' => esc_attr(get_the_title($next_post)),
                                    'loading' => 'lazy'
                                ]); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                        </svg>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <style>
        /* Enhanced Post Navigation Styles */
        .post-navigation {
            margin: 3rem 0;
            padding: 2rem 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .post-navigation .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .post-navigation .nav-previous,
        .post-navigation .nav-next {
            flex: 1;
            max-width: 400px;
        }

        .post-navigation .nav-next {
            margin-left: auto;
            text-align: right;
        }

        .post-navigation .nav-link {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #4a5568;
            transition: all 0.2s ease;
            min-height: 100px;
        }

        .post-navigation .nav-link:hover {
            background: #ffffff;
            border-color: #3182ce;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #3182ce;
        }

        .post-navigation .nav-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .post-navigation .nav-link:hover .nav-icon {
            background: #3182ce;
            color: #ffffff;
        }

        .post-navigation .nav-content {
            flex: 1;
            min-width: 0;
        }

        .post-navigation .nav-subtitle {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #718096;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .post-navigation .nav-title {
            display: block;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.4;
            color: inherit;
            margin-bottom: 0.75rem;
        }

        .post-navigation .nav-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .post-navigation .nav-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dark mode support */
        body.dark-mode .post-navigation {
            border-color: #334155;
        }

        body.dark-mode .post-navigation .nav-link {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e0;
        }

        body.dark-mode .post-navigation .nav-link:hover {
            background: #334155;
            border-color: #3b82f6;
            color: #60a5fa;
        }

        body.dark-mode .post-navigation .nav-icon {
            background: #334155;
        }

        body.dark-mode .post-navigation .nav-link:hover .nav-icon {
            background: #3b82f6;
            color: #ffffff;
        }

        body.dark-mode .post-navigation .nav-subtitle {
            color: #94a3b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .post-navigation .nav-links {
                flex-direction: column;
                gap: 1rem;
            }

            .post-navigation .nav-previous,
            .post-navigation .nav-next {
                max-width: none;
                text-align: left;
                margin-left: 0;
            }

            .post-navigation .nav-link {
                padding: 1rem;
                min-height: 80px;
            }

            .post-navigation .nav-title {
                font-size: 1rem;
            }

            .post-navigation .nav-thumbnail {
                width: 50px;
                height: 50px;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            .post-navigation .nav-link {
                transition: none;
            }

            .post-navigation .nav-link:hover {
                transform: none;
            }
        }

        /* Focus styles for keyboard navigation */
        .post-navigation .nav-link:focus {
            outline: 2px solid #3182ce;
            outline-offset: 2px;
        }
    </style>
</nav><!-- .post-navigation -->
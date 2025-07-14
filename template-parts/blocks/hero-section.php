<?php

/**
 * ACF Block Templates
 * Create the following files in your theme directory
 */

// ===== template-parts/blocks/hero-section.php =====
?>
<div class="hero-section-block <?php echo esc_attr($block['className'] ?? ''); ?>"
    id="<?php echo esc_attr($block['anchor'] ?? ''); ?>">

    <?php
    $hero_title = get_field('hero_title') ?: __('Welcome to Our Site', 'attribute-canva');
    $hero_subtitle = get_field('hero_subtitle') ?: __('This is a sample subtitle', 'attribute-canva');
    $hero_image = get_field('hero_image');
    $hero_button_text = get_field('hero_button_text') ?: __('Learn More', 'attribute-canva');
    $hero_button_url = get_field('hero_button_url') ?: '#';
    $hero_style = get_field('hero_style') ?: 'centered';
    ?>

    <div class="hero-content hero-style-<?php echo esc_attr($hero_style); ?>">
        <?php if ($hero_image): ?>
            <div class="hero-background">
                <img src="<?php echo esc_url($hero_image['url']); ?>"
                    alt="<?php echo esc_attr($hero_image['alt']); ?>" />
            </div>
        <?php endif; ?>

        <div class="hero-text">
            <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>

            <?php if ($hero_button_text && $hero_button_url): ?>
                <a href="<?php echo esc_url($hero_button_url); ?>" class="hero-button btn btn-primary">
                    <?php echo esc_html($hero_button_text); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .hero-section-block {
        position: relative;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-text {
        position: relative;
        z-index: 2;
        color: #fff;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .hero-title {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
    }

    .hero-button {
        padding: 12px 30px;
        background: #0073aa;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s ease;
    }

    .hero-button:hover {
        background: #005177;
    }
</style>

<?php
// ===== template-parts/blocks/testimonial.php =====
$testimonial_quote = get_field('testimonial_quote') ?: __('This is a sample testimonial quote.', 'attribute-canva');
$testimonial_author = get_field('testimonial_author') ?: __('John Doe', 'attribute-canva');
$testimonial_position = get_field('testimonial_position') ?: __('CEO, Company', 'attribute-canva');
$testimonial_image = get_field('testimonial_image');
$testimonial_rating = get_field('testimonial_rating') ?: 5;
?>

<div class="testimonial-block <?php echo esc_attr($block['className'] ?? ''); ?>"
    id="<?php echo esc_attr($block['anchor'] ?? ''); ?>">

    <div class="testimonial-content">
        <div class="testimonial-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star <?php echo $i <= $testimonial_rating ? 'filled' : 'empty'; ?>">★</span>
            <?php endfor; ?>
        </div>

        <blockquote class="testimonial-quote">
            "<?php echo esc_html($testimonial_quote); ?>"
        </blockquote>

        <div class="testimonial-author-info">
            <?php if ($testimonial_image): ?>
                <div class="testimonial-avatar">
                    <img src="<?php echo esc_url($testimonial_image['url']); ?>"
                        alt="<?php echo esc_attr($testimonial_image['alt']); ?>" />
                </div>
            <?php endif; ?>

            <div class="testimonial-author-details">
                <cite class="testimonial-author"><?php echo esc_html($testimonial_author); ?></cite>
                <span class="testimonial-position"><?php echo esc_html($testimonial_position); ?></span>
            </div>
        </div>
    </div>
</div>

<style>
    .testimonial-block {
        padding: 2rem;
        background: #f9f9f9;
        border-radius: 8px;
        text-align: center;
        margin: 2rem 0;
    }

    .testimonial-rating {
        margin-bottom: 1rem;
    }

    .star.filled {
        color: #ffd700;
    }

    .star.empty {
        color: #ddd;
    }

    .testimonial-quote {
        font-size: 1.25rem;
        font-style: italic;
        margin: 1rem 0 2rem;
        color: #333;
    }

    .testimonial-author-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .testimonial-avatar img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .testimonial-author-details {
        text-align: left;
    }

    .testimonial-author {
        display: block;
        font-weight: bold;
        color: #333;
    }

    .testimonial-position {
        color: #666;
        font-size: 0.9rem;
    }
</style>

<?php
// ===== template-parts/blocks/feature-grid.php =====
$features = get_field('features');
$grid_columns = get_field('grid_columns') ?: 3;
?>

<div class="feature-grid-block <?php echo esc_attr($block['className'] ?? ''); ?>"
    id="<?php echo esc_attr($block['anchor'] ?? ''); ?>">

    <?php if ($features): ?>
        <div class="features-container" style="grid-template-columns: repeat(<?php echo esc_attr($grid_columns); ?>, 1fr);">
            <?php foreach ($features as $feature): ?>
                <div class="feature-item">
                    <?php if ($feature['icon']): ?>
                        <div class="feature-icon">
                            <img src="<?php echo esc_url($feature['icon']['url']); ?>"
                                alt="<?php echo esc_attr($feature['icon']['alt']); ?>" />
                        </div>
                    <?php endif; ?>

                    <h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
                    <p class="feature-description"><?php echo esc_html($feature['description']); ?></p>

                    <?php if ($feature['link']): ?>
                        <a href="<?php echo esc_url($feature['link']['url']); ?>"
                            class="feature-link"
                            <?php echo $feature['link']['target'] ? 'target="_blank"' : ''; ?>>
                            <?php echo esc_html($feature['link']['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .feature-grid-block {
        padding: 2rem 0;
    }

    .features-container {
        display: grid;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-item {
        text-align: center;
        padding: 2rem;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .feature-item:hover {
        transform: translateY(-5px);
    }

    .feature-icon img {
        width: 80px;
        height: 80px;
        margin-bottom: 1rem;
    }

    .feature-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .feature-description {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .feature-link {
        color: #0073aa;
        text-decoration: none;
        font-weight: bold;
    }

    .feature-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .features-container {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php
// ===== template-parts/blocks/call-to-action.php =====
$cta_title = get_field('cta_title') ?: __('Ready to Get Started?', 'attribute-canva');
$cta_description = get_field('cta_description') ?: __('Join thousands of satisfied customers today.', 'attribute-canva');
$cta_button_text = get_field('cta_button_text') ?: __('Get Started', 'attribute-canva');
$cta_button_url = get_field('cta_button_url') ?: '#';
$cta_background_color = get_field('cta_background_color') ?: '#0073aa';
$cta_text_color = get_field('cta_text_color') ?: '#ffffff';
?>

<div class="cta-block <?php echo esc_attr($block['className'] ?? ''); ?>"
    id="<?php echo esc_attr($block['anchor'] ?? ''); ?>"
    style="background-color: <?php echo esc_attr($cta_background_color); ?>; color: <?php echo esc_attr($cta_text_color); ?>;">

    <div class="cta-content">
        <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
        <p class="cta-description"><?php echo esc_html($cta_description); ?></p>

        <?php if ($cta_button_text && $cta_button_url): ?>
            <a href="<?php echo esc_url($cta_button_url); ?>" class="cta-button">
                <?php echo esc_html($cta_button_text); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
    .cta-block {
        padding: 4rem 2rem;
        text-align: center;
        margin: 2rem 0;
    }

    .cta-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: bold;
    }

    .cta-description {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .cta-button {
        display: inline-block;
        padding: 15px 40px;
        background: rgba(255, 255, 255, 0.2);
        color: inherit;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .cta-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .cta-title {
            font-size: 2rem;
        }

        .cta-description {
            font-size: 1.1rem;
        }
    }
</style>
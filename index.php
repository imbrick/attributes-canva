<?php get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="entry-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span> |
                        <span class="post-author"><?php the_author_posts_link(); ?></span> |
                        <span class="post-categories"><?php the_category(', '); ?></span>
                    </div>
                </header>
                <div class="entry-content">
                    <?php 
                    add_filter('excerpt_length', function() { return 20; }); 
                    the_excerpt(); 
                    ?>
                </div>
            </article>
        <?php endwhile; ?>

        <nav class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('&laquo; Previous', 'attribute-canva'),
                'next_text' => __('Next &raquo;', 'attribute-canva'),
                'screen_reader_text' => __('Posts navigation', 'attribute-canva'),
            ));
            ?>
        </nav>
    <?php else : ?>
        <p><?php _e('No content available. Please check back later.', 'attribute-canva'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

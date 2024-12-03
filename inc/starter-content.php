<?php
/**
 * Starter Content, Demo Import, Theme Docs, Typography Settings, Contact Widget, and Admin Notices
 */

if (!function_exists('attribute_canva_starter_content')) {
    /**
     * Define and register starter content for the theme
     */
    function attribute_canva_starter_content() {
        $starter_content = array(
            // Define some example posts
            'posts' => array(
                'home' => array(
                    'post_type'    => 'page',
                    'post_title'   => __('Welcome to Attribute Canva', 'attr-canva'),
                    'post_content' => __('This is your homepage. You can edit this page to customize it for your site.', 'attr-canva'),
                ),
                'about' => array(
                    'post_type'    => 'page',
                    'post_title'   => __('About Us', 'attr-canva'),
                    'post_content' => __('This is an example about us page. Share your story here.', 'attr-canva'),
                ),
                'blog' => array(
                    'post_type'    => 'page',
                    'post_title'   => __('Blog', 'attr-canva'),
                ),
                'contact' => array(
                    'post_type'    => 'page',
                    'post_title'   => __('Contact', 'attr-canva'),
                    'post_content' => __('Add your contact details or a form here.', 'attr-canva'),
                ),
            ),
            // Configure front page and blog settings
            'options' => array(
                'show_on_front'  => 'page',
                'page_on_front'  => '{{home}}',
                'page_for_posts' => '{{blog}}',
            ),
            // Define navigation menus
            'nav_menus' => array(
                // Assign a menu to the 'primary' location
                'primary' => array(
                    'name' => __('Primary Menu', 'attr-canva'),
                    'items' => array(
                        'link_home',    // Home link
                        'page_about',   // Link to About Us page
                        'page_blog',    // Link to Blog page
                        'page_contact', // Link to Contact page
                    ),
                ),
            ),
            // Adding widgets to the sidebar and footer
            'widgets' => array(
                'sidebar' => array(
                    'text_about' => array(
                        'text' => __('This is a sidebar widget example.', 'attr-canva'),
                    ),
                ),
                'footer' => array(
                    'text_contact' => array(
                        'text' => __('Add your contact details here.', 'attr-canva'),
                    ),
                ),
            ),
        );

        // Add support for starter content in the theme
        add_theme_support('starter-content', $starter_content);
    }
    add_action('after_setup_theme', 'attribute_canva_starter_content');
}

if (!function_exists('attribute_canva_theme_docs')) {
    /**
     * Add Theme Docs menu to WordPress Admin
     */
    function attribute_canva_theme_docs() {
        add_menu_page(
            __('Theme Docs', 'attr-canva'),
            __('Theme Docs', 'attr-canva'),
            'edit_theme_options',
            'theme-docs',
            function () {
                $file = get_template_directory() . '/docs/documentation.html';
                if (file_exists($file)) {
                    echo file_get_contents($file);
                } else {
                    echo '<p>' . __('Documentation file not found.', 'attr-canva') . '</p>';
                }
            },
            'dashicons-book',
            3
        );
    }
    add_action('admin_menu', 'attribute_canva_theme_docs');
}

if (!function_exists('attribute_canva_demo_import')) {
    /**
     * Setup demo import configuration
     */
    function attribute_canva_demo_import() {
        if (!class_exists('OCDI_Plugin')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p>' . __('The "One Click Demo Import" plugin is required for importing demo content.', 'attr-canva') . '</p>';
                echo '</div>';
            });
            return;
        }

        add_filter('pt-ocdi/import_files', function () {
            return array(
                array(
                    'import_file_name' => 'Demo Content',
                    'local_import_file' => get_template_directory() . '/demo-content/demo-content.xml',
                    'local_import_widget_file' => get_template_directory() . '/demo-content/widgets.wie',
                    'import_preview_image_url' => get_template_directory_uri() . '/assets/images/demo-preview.png',
                    'import_notice' => __('Important: Install recommended plugins before importing.', 'attr-canva'),
                ),
            );
        });
    }
    add_action('after_setup_theme', 'attribute_canva_demo_import');
}

if (!function_exists('attribute_canva_customize_register')) {
    /**
     * Add Typography Settings to the Customizer
     */
    function attribute_canva_customize_register($wp_customize) {
        $wp_customize->add_section('attribute_canva_typography', array(
            'title'    => __('Typography', 'attr-canva'),
            'priority' => 40,
        ));

        $wp_customize->add_setting('attribute_canva_body_font', array(
            'default'   => 'Arial',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('attribute_canva_body_font', array(
            'label'    => __('Body Font', 'attr-canva'),
            'section'  => 'attribute_canva_typography',
            'type'     => 'select',
            'choices'  => array(
                'Arial'   => 'Arial',
                'Verdana' => 'Verdana',
                'Tahoma'  => 'Tahoma',
            ),
        ));
    }
    add_action('customize_register', 'attribute_canva_customize_register');
}

if (!class_exists('Attribute_Canva_Contact_Widget')) {
    /**
     * Contact Widget
     */
    class Attribute_Canva_Contact_Widget extends WP_Widget {
        function __construct() {
            parent::__construct('attribute_contact', __('Contact Info', 'attr-canva'), array(
                'description' => __('Display contact information.', 'attr-canva'),
            ));
        }

        function widget($args, $instance) {
            echo $args['before_widget'];
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
            echo '<p>' . esc_html($instance['contact_info']) . '</p>';
            echo $args['after_widget'];
        }

        function form($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
            $contact_info = isset($instance['contact_info']) ? $instance['contact_info'] : '';
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'attr-canva'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('contact_info'); ?>"><?php _e('Contact Info:', 'attr-canva'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('contact_info'); ?>" name="<?php echo $this->get_field_name('contact_info'); ?>"><?php echo esc_textarea($contact_info); ?></textarea>
            </p>
            <?php
        }
    }

    function attribute_canva_register_widgets() {
        register_widget('Attribute_Canva_Contact_Widget');
    }
    add_action('widgets_init', 'attribute_canva_register_widgets');
}

if (!function_exists('attribute_canva_welcome_notice')) {
    /**
     * Admin Welcome Notice
     */
    function attribute_canva_welcome_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Welcome to Attribute Canva! Start by visiting the <a href="admin.php?page=theme-docs">Theme Docs</a> or customizing your site in the <a href="customize.php">Customizer</a>.', 'attr-canva'); ?></p>
        </div>
        <?php
    }
    add_action('admin_notices', 'attribute_canva_welcome_notice');
}

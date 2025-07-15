<?php
// At the top of custom-widget.php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if Elementor is loaded and the Widget_Base class exists
if (!class_exists('Elementor\Widget_Base')) {
    return;
}

/**
 * Elementor Custom Widget
 *
 * @package Attribute Canva
 */
class Elementor_Custom_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'custom_widget';
    }

    public function get_title()
    {
        return __('Custom Widget', 'attribute-canva');
    }

    public function get_icon()
    {
        return 'eicon-code';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'attribute-canva'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'attribute-canva'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Hello World', 'attribute-canva'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo '<h2>' . $settings['title'] . '</h2>';
    }
}

// Register the widget
function attribute_canva_register_custom_widget($widgets_manager)
{
    require_once(__DIR__ . '/elementor-widgets/custom-widget.php');
    $widgets_manager->register(new \Elementor_Custom_Widget());
}
add_action('elementor/widgets/register', 'attribute_canva_register_custom_widget');

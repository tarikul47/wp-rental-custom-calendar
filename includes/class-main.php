<?php

class Main
{
    public function __construct()
    {
        // Register the shortcode for displaying the calendar
        add_shortcode('my_calendar', [$this, 'render_calendar']);

        // Enqueue CSS and JS
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets'], 100);

        // Hook to register the template
        add_filter('theme_page_templates', array($this, 'register_plugin_templates'));
        // Hook to include the template
        add_filter('template_include', array($this, 'load_plugin_template'));
    }

    // Register the custom template in the WordPress page editor
    public function register_plugin_templates($templates)
    {
        $templates['calendar-template.php'] = __('Our Custom Calendar', 'my-custom-calendar');
        return $templates;
    }


    // Load the custom template when selected
    public function load_plugin_template($template)
    {
        global $post;

        if (!$post) {
            return $template;
        }
        // Check if the selected template is our custom template
        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        if (isset($template_name) && $template_name === 'calendar-template.php') {
            // Define the path to your custom template file
            $file = MY_CALENDAR_PLUGIN_PATH . '/templates/' . $template_name;
            if (file_exists($file)) {
                return $file;
            }
        }
        return $template;
    }


    // Enqueue styles and scripts
    public function enqueue_assets()
    {
        global $post;

        // Check if the current post is using your custom template
        if ($post && get_post_meta($post->ID, '_wp_page_template', true) === 'calendar-template.php') {
            // Enqueue the custom CSS and JS
            wp_enqueue_style('my-calendar-css', MY_CALENDAR_PLUGIN_URL . 'assets/css/calendar.css', array(), '1.0', 'all');
            wp_enqueue_script('my-calendar-js', MY_CALENDAR_PLUGIN_URL . 'assets/js/calendar.js', array('jquery'), '1.0', true);
        }
    }

}
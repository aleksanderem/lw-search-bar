<?php
if (!defined('ABSPATH')) exit;

class LW_Elementor {

    public function __construct() {
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_assets']);

        // Elementor 3.5+ uses 'elementor/widgets/register', older versions use 'elementor/widgets/widgets_registered'
        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.5.0', '>=')) {
            add_action('elementor/widgets/register', [$this, 'register_widgets_new']);
        } else {
            add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets_old']);
        }
    }

    public function enqueue_preview_assets() {
        LW_Shortcode::enqueue_shared_assets();
    }

    public function register_category($elements_manager) {
        $elements_manager->add_category('lw-search-bar', [
            'title' => 'LW Search Bar',
            'icon'  => 'eicon-search',
        ]);
    }

    private function load_widget_files() {
        require_once LW_SEARCH_DIR . 'includes/widgets/class-lw-search-form-widget.php';
        require_once LW_SEARCH_DIR . 'includes/widgets/class-lw-search-results-widget.php';
    }

    public function register_widgets_new($widgets_manager) {
        $this->load_widget_files();
        $widgets_manager->register(new LW_Search_Form_Widget());
        $widgets_manager->register(new LW_Search_Results_Widget());
    }

    public function register_widgets_old($widgets_manager) {
        $this->load_widget_files();
        $widgets_manager->register_widget_type(new LW_Search_Form_Widget());
        $widgets_manager->register_widget_type(new LW_Search_Results_Widget());
    }
}

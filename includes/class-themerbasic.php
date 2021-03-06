<?php

class ThemerBasic {

    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
        add_action( 'init', array( $this, 'register_side_bars' ) );
        add_action( 'themerbasic_header', array( $this, 'print_default_header' ) );
        add_action( 'themerbasic_footer', array( $this, 'print_default_footer' ) );
        add_action( 'after_setup_theme', array( $this, 'register_beaver_themer_support' ) );
        add_action( 'wp', array( $this, 'header_footer_render' ) );
        add_filter( 'fl_theme_builder_part_hooks', array( $this, 'register_part_hooks' ) );
        //add_action( 'customize_register', array( 'ThemerBasic_Customizer', 'register' ) );
    }

    /**
     * Turn on WordPress features
     */
    public function add_theme_support() {
        /**
         * Turn on menu functionality
         */
        add_theme_support( 'menus' );
        register_nav_menu('main-menu', 'Main Menu');

        /**
         * Activate featured images
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * Enable support for <title> tag
         */
        add_theme_support( 'title-tag' );

        /**
         * Add HTML5 theme support
         */
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    }

    /**
     * Register a sidebar
     */
    public function register_side_bars() {
        register_sidebar( [
            'name'          => 'Sidebar',
            'id'            => 'sidebar',
            'before_widget' => '<div class="widget clearfix">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ] );
    }

    /**
     * Register support for Beaver Themer
     */
    public function register_beaver_themer_support() {
        add_theme_support( 'fl-theme-builder-headers' );
        add_theme_support( 'fl-theme-builder-footers' );
        add_theme_support( 'fl-theme-builder-parts' );
    }

    /**
     * Activate Beaver Themer header and footer
     */
    public function header_footer_render() {

        if ( ! class_exists( 'FLThemeBuilderLayoutData' ) ) {
            return;
        }

        // Get the header ID.
        $header_ids = FLThemeBuilderLayoutData::get_current_page_header_ids();

        // If we have a header, remove the theme header and hook in Theme Builder's.
        if ( ! empty( $header_ids ) ) {
            remove_action( 'themerbasic_header', array( $this, 'print_default_header' ) );
            add_action( 'themerbasic_header', 'FLThemeBuilderLayoutRenderer::render_header' );
        }

        // Get the footer ID.
        $footer_ids = FLThemeBuilderLayoutData::get_current_page_footer_ids();

        // If we have a footer, remove the theme footer and hook in Theme Builder's.
        if ( ! empty( $footer_ids ) ) {
            remove_action( 'themerbasic_footer', array( $this, 'print_default_footer' ) );
            add_action( 'themerbasic_footer', 'FLThemeBuilderLayoutRenderer::render_footer' );
        }
    }

    public function print_default_header() {
        get_template_part('templates/header');
    }

    public function print_default_footer() {
        get_template_part('templates/footer');
    }

    /**
     * Register Beaver Themer Parts
     * @return array
     */
    public function register_part_hooks() {
        return array(
            array(
                'label' => 'Header',
                'hooks' => array(
                    'themerbasic_before_header' => 'Before Header',
                    'themerbasic_after_header'  => 'After Header',
                ),
            ),
            array(
                'label' => 'Content',
                'hooks' => array(
                    'themerbasic_before_content' => 'Before Content',
                    'themerbasic_after_content'  => 'After Content',
                ),
            ),
            array(
                'label' => 'Footer',
                'hooks' => array(
                    'themerbasic_before_footer' => 'Before Footer',
                    'themerbasic_after_footer'  => 'After Footer',
                ),
            ),
        );
    }
}

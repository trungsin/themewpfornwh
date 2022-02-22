<?php
/**
 * Infinity Blog functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Infinity Blog
 */

if (!function_exists('infinity_blog_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function infinity_blog_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Infinity Blog, use a find and replace
         * to change 'infinity-blog' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'infinity-blog', get_template_directory() . '/languages' );



        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for custom logo.
         */
        add_theme_support('custom-logo', array(
            'header-text' => array('site-title', 'site-description'),
        ));
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        add_image_size('infinity-blog-related-post', 700, 465, true);


        // Set up the WordPress core custom header feature.
        add_theme_support('custom-header', apply_filters('infinity_blog_custom_header_args', array(
            'width' => 1920,
            'height' => 600,
            'flex-height' => true,
            'header-text' => false,
        )));

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'infinity-blog'),
            'social' => esc_html__('Social Menu', 'infinity-blog'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('infinity_blog_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'image',
            'video',
            'gallery',
            'audio',
        ));

        /*
         * Enable support Gutenberg and Block styles.
         *
         */
        add_theme_support( 'align-wide' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'wp-block-styles' );

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, and column width.
         */
        add_editor_style( 'assets/twp/css/editor-style.css' );

        /**
         * Load Init for Hook files.
         */
        require get_template_directory() . '/inc/hooks/hooks-init.php';

    }
endif;
add_action('after_setup_theme', 'infinity_blog_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function infinity_blog_content_width()
{
    $GLOBALS['content_width'] = apply_filters('infinity_blog_content_width', 640);
}

add_action('after_setup_theme', 'infinity_blog_content_width', 0);
add_theme_support( 'infinite-scroll', array(
 'container' => 'content',
 'footer' => 'page',
) );
/**
 * Enqueue scripts and styles.
 */
function infinity_blog_scripts()
{   
    $theme_version = wp_get_theme()->get( 'Version' );
    $fonts_url = infinity_blog_fonts_url();
    if( $fonts_url ){
        
        require_once get_theme_file_path( 'assets/twp/css/wptt-webfont-loader.php' );
        wp_enqueue_style(
            'infinity-blog-google-fonts',
            wptt_get_webfont_url( $fonts_url ),
            array(),
            $theme_version
        );
    }

    wp_enqueue_style('owlcarousel', get_template_directory_uri() . '/assets/libraries/owlcarousel/css/owl.carousel.css');
    wp_enqueue_style('ionicons', get_template_directory_uri() . '/assets/libraries/ionicons/css/ionicons.min.css');
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/libraries/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/libraries/magnific-popup/magnific-popup.css');
    wp_enqueue_style('infinity-blog-style', get_stylesheet_uri());
    /*inline style*/
    wp_add_inline_style('infinity-blog-style', infinity_blog_trigger_custom_css_action());

    wp_enqueue_script('headroom', get_template_directory_uri() . '/assets/libraries/headroom/headroom.js', array('jquery'), '', true);
    wp_enqueue_script('jquery-headroom', get_template_directory_uri() . '/assets/libraries/headroom/jQuery.headroom.js', array('jquery'), '', true);
    wp_enqueue_script('owlcarousel', get_template_directory_uri() . '/assets/libraries/owlcarousel/js/owl.carousel.min.js', array('jquery'), '', true);
    wp_enqueue_script('theiaStickySidebar', get_template_directory_uri() . '/assets/libraries/theiaStickySidebar/theia-sticky-sidebar.min.js', array('jquery'), '', true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/libraries/bootstrap/js/bootstrap.min.js', array('jquery'), '', true);
    wp_enqueue_script('jquery-magnific-popup', get_template_directory_uri() . '/assets/libraries/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), '', true);
    wp_enqueue_script('match-height', get_template_directory_uri() . '/assets/libraries/jquery-match-height/js/jquery.matchHeight.min.js', array('jquery'), '', true);


    /*For Ajax Load Posts*/
    $args = array(
        'nonce' => wp_create_nonce( 'infinity-blog-load-more-nonce' ),
        'ajaxurl'   => admin_url( 'admin-ajax.php' ),
    );
    if( is_front_page() ){
        $args['post_type'] = 'post';
    }

    /*Support for custom post types*/
    if( is_post_type_archive() ){
        $args['post_type'] = get_queried_object()->name;
    }
    /**/

    /*Support for categories and taxonomies*/
    if( is_category() || is_tag() || is_tax() ){
        $args['cat'] = get_queried_object()->slug;
        $args['taxonomy'] = get_queried_object()->taxonomy;
        /*Get the associated post type for custom taxonomy*/
        if( is_tax() ){
            global $wp_taxonomies;
            $tax_object = isset( $wp_taxonomies[$args['taxonomy']] ) ? $wp_taxonomies[$args['taxonomy']]->object_type : array();
            $args['post_type'] = array_pop($tax_object);
        }
        /**/
    }
    /**/

    /*Support for search*/
    if( is_search() ){
        $args['search'] = get_search_query();
    }
    /**/

    /*Support for author*/
    if( is_author() ){
        $args['author'] = get_the_author_meta( 'user_nicename' ) ;
    }
    /**/

    /*Support for date archive*/
    if( is_date() ){
        $args['year'] = get_query_var('year');
        $args['month'] = get_query_var('monthnum');
        $args['day'] = get_query_var('day');
    }
    /**/

    wp_enqueue_script('infinity-blog-script', get_template_directory_uri() . '/assets/twp/js/custom-script.js', array( 'jquery', 'wp-mediaelement'), '', true);
    wp_localize_script( 'infinity-blog-script', 'infinityVal', $args );


    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'infinity_blog_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function infinity_blog_admin_scripts($hook)
{   
    wp_enqueue_style('infinity-blog-custom-admin-style', get_template_directory_uri() . '/assets/twp/css/admin.css', array(), '1.0.0');

    wp_enqueue_media();
    wp_enqueue_script('infinity-blog-admin', get_template_directory_uri() . '/assets/twp/js/admin.js', array('jquery'), '1.0.0', true);

    $ajax_nonce = wp_create_nonce('infinity_blog_ajax_nonce');
            
    wp_localize_script( 
        'infinity-blog-admin', 
        'infinity_blog_admin',
        array(
            'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
            'ajax_nonce' => $ajax_nonce,
            'active' => esc_html__('Active','infinity-blog'),
            'deactivate' => esc_html__('Deactivate','infinity-blog'),
            'upload_image'   =>  esc_html__('Choose Image','infinity-blog'),
            'use_image'   =>  esc_html__('Select','infinity-blog'),
         )
    );

}

add_action('admin_enqueue_scripts', 'infinity_blog_admin_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

require get_template_directory() . '/classes/about.php';
require get_template_directory() . '/classes/admin-notice.php';
require get_template_directory() . '/classes/plugins-classes.php';

if( class_exists('Demo_Import_Kit_Class') ):

    add_filter('themeinwp_enable_demo_import_compatiblity','infinity_blog_demo_import_filter_apply');

    if( !function_exists('infinity_blog_demo_import_filter_apply') ):

        function infinity_blog_demo_import_filter_apply(){

            return true;

        }

    endif;

endif;
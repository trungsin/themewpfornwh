<?php

/**
 * Infinity Blog About Page
 * @package Infinity Blog
 *
*/

if( !class_exists('Infinity_Blog_About_page') ):

	class Infinity_Blog_About_page{

		function __construct(){

			add_action('admin_menu', array($this, 'infinity_blog_backend_menu'),999);

		}

		// Add Backend Menu
        function infinity_blog_backend_menu(){

            add_theme_page(esc_html__( 'Infinity Blog Options','infinity-blog' ), esc_html__( 'Infinity Blog Options','infinity-blog' ), 'activate_plugins', 'infinity-blog-about', array($this, 'infinity_blog_main_page'));

        }

        // Settings Form
        function infinity_blog_main_page(){

            require get_template_directory() . '/classes/about-render.php';

        }

	}

	new Infinity_Blog_About_page();

endif;
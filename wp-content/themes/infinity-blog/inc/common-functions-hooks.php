<?php
if (!function_exists('infinity_blog_the_custom_logo')):
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since Infinity Blog 1.0.0
 */
function infinity_blog_the_custom_logo() {
	if (function_exists('the_custom_logo')) {
		the_custom_logo();
	}
}
endif;

if (!function_exists('infinity_blog_body_class')):

/**
 * body class.
 *
 * @since 1.0.0
 */
function infinity_blog_body_class($infinity_blog_body_class) {
	global $post;
	$global_layout       = infinity_blog_get_option('global_layout');
	$input               = '';
	$home_content_status = infinity_blog_get_option('home_page_content_status');
	if (1 != $home_content_status) {
		$input = 'home-content-not-enabled';
	}
	// Check if single.
	if ($post && is_singular()) {
		$post_options = get_post_meta($post->ID, 'infinity-blog-meta-select-layout', true);
		if (empty($post_options)) {
			$global_layout = esc_attr(infinity_blog_get_option('global_layout'));
		} else {
			$global_layout = esc_attr($post_options);
		}
	}
	if ($global_layout == 'left-sidebar') {
		$infinity_blog_body_class[] = 'left-sidebar '.esc_attr($input);
	} elseif ($global_layout == 'no-sidebar') {
		$infinity_blog_body_class[] = 'no-sidebar '.esc_attr($input);
	} else {
		$infinity_blog_body_class[] = 'right-sidebar '.esc_attr($input);

	}
	return $infinity_blog_body_class;
}
endif;

add_action('body_class', 'infinity_blog_body_class');

add_action('infinity_blog_action_sidebar', 'infinity_blog_add_sidebar');

/**
 * Returns word count of the sentences.
 *
 * @since Infinity Blog 1.0.0
 */
if (!function_exists('infinity_blog_words_count')):
function infinity_blog_words_count($length = 25, $infinity_blog_content = null) {
	$length          = absint($length);
	$source_content  = preg_replace('`\[[^\]]*\]`', '', $infinity_blog_content);
	$trimmed_content = wp_trim_words($source_content, $length, '');
	return $trimmed_content;
}
endif;

if (!function_exists('infinity_blog_simple_breadcrumb')):

/**
 * Simple breadcrumb.
 *
 * @since 1.0.0
 */
function infinity_blog_simple_breadcrumb() {

	if (!function_exists('breadcrumb_trail')) {

		require_once get_template_directory().'/assets/libraries/breadcrumbs/breadcrumbs.php';
	}

	$breadcrumb_args = array(
		'container'   => 'div',
		'show_browse' => false,
	);
	breadcrumb_trail($breadcrumb_args);

}

endif;



if ( ! function_exists( 'infinity_blog_ajax_pagination' ) ) :
    /**
     * Outputs the required structure for ajax loading posts on scroll and click
     *
     * @since 1.0.0
     * @param $type string Ajax Load Type
     */
    function infinity_blog_ajax_pagination($type) {
        ?>
        <div class="load-more-posts" data-load-type="<?php echo esc_attr($type);?>">
            <a href="javascript:void(0)" class="btn-link btn-link-load">
                <span class="ajax-loader"></span>
                <?php _e('Load More Posts', 'infinity-blog')?>
                <i class="ion-ios-arrow-right"></i>
            </a>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'infinity_blog_load_more' ) ) :
    /**
     * Ajax Load posts Callback.
     *
     * @since 1.0.0
     *
     */
    function infinity_blog_load_more() {

        check_ajax_referer( 'infinity-blog-load-more-nonce', 'nonce' );

        $output['more_post'] = false;
        $output['content'] = array();

        $args['post_type'] = ( isset( $_GET['post_type']) && !empty($_GET['post_type'] ) ) ? esc_attr( $_GET['post_type'] ) : 'post';
        $args['post_status'] = 'publish';
        $args['paged'] = (int) esc_attr( $_GET['page'] );

        if( isset( $_GET['cat'] ) && isset( $_GET['taxonomy'] ) ){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => esc_attr($_GET['taxonomy']),
                    'field'    => 'slug',
                    'terms'    => array(esc_attr($_GET['cat'])),
                ),
            );
        }

        if( isset($_GET['search']) ){
            $args['s'] = esc_attr( $_GET['search'] );
        }

        if( isset($_GET['author']) ){
            $args['author_name'] = esc_attr( $_GET['author'] );
        }

        if( isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day']) ){

            $date_arr = array();

            if( !empty($_GET['year']) ){
                $date_arr['year'] = (int) esc_attr($_GET['year']);
            }
            if( !empty($_GET['month']) ){
                $date_arr['month'] = (int) esc_attr($_GET['month']);
            }
            if( !empty($_GET['day']) ){
                $date_arr['day'] = (int) esc_attr($_GET['day']);
            }

            if( !empty($date_arr) ){
                $args['date_query'] = array($date_arr);
            }
        }

        $loop = new WP_Query( $args );
        if($loop->max_num_pages > $args['paged']){
            $output['more_post'] = true;
        }
        if ( $loop->have_posts() ):
            while ( $loop->have_posts() ): $loop->the_post();
                ob_start();
                get_template_part('template-parts/content', get_post_format());
                $output['content'][] = ob_get_clean();
            endwhile;wp_reset_postdata();
            wp_send_json_success($output);
        else:
            $output['more_post'] = false;
            wp_send_json_error($output);
        endif;
        wp_die();
    }
endif;
add_action( 'wp_ajax_infinity_blog_load_more', 'infinity_blog_load_more' );
add_action( 'wp_ajax_nopriv_infinity_blog_load_more', 'infinity_blog_load_more' );


if (!function_exists('infinity_blog_custom_posts_navigation')):
/**
 * Posts navigation.
 *
 * @since 1.0.0
 */
function infinity_blog_custom_posts_navigation() {

	$pagination_type = infinity_blog_get_option('pagination_type');

	switch ($pagination_type) {

		case 'default':
			the_posts_navigation();
			break;

		case 'numeric':
			the_posts_pagination();
			break;

        case 'infinite_scroll_load':
            infinity_blog_ajax_pagination('scroll');
            break;

		default:
			break;
	}

}
endif;

add_action('infinity_blog_action_posts_navigation', 'infinity_blog_custom_posts_navigation');

if (!function_exists('infinity_blog_excerpt_length')):

/**
 * Excerpt length
 *
 * @since  Infinity Blog 1.0.0
 *
 * @param null
 * @return int
 */
function infinity_blog_excerpt_length($length) {
    if ( is_admin() ) {
            return $length;
    }
	$excerpt_length = infinity_blog_get_option('excerpt_length_global');
	if (empty($excerpt_length)) {
		$excerpt_length = $length;
	}
	return absint($excerpt_length);

}

add_filter('excerpt_length', 'infinity_blog_excerpt_length', 999);
endif;

if (!function_exists('infinity_blog_excerpt_more') ):

/**
 * Implement read more in excerpt.
 *
 * @since 1.0.0
 *
 * @param string $more The string shown within the more link.
 * @return string The excerpt.
 */
function infinity_blog_excerpt_more($more) {
    if ( is_admin() ) {
            return $more;
    }
	$flag_apply_excerpt_read_more = apply_filters('infinity_blog_filter_excerpt_read_more', true);
	if (true !== $flag_apply_excerpt_read_more) {
		return $more;
	}

	$output         = $more;
	$read_more_text = esc_html(infinity_blog_get_option('read_more_button_text'));
	if (!empty($read_more_text)) {
        if ( wp_is_mobile() ) {
            $output = ' <br><a href="'.esc_url(get_permalink()).'" class="readmorebtn">'.esc_html($read_more_text).'<i class="ion-ios-arrow-right"></i><i class="ion-ios-arrow-right"></i>'.'</a>';
            $output = apply_filters('infinity_blog_filter_read_more_link', $output);
        } else {
            $output = ' <a href="'.esc_url(get_permalink()).'" class="btn-link">'.esc_html($read_more_text).'<i class="ion-ios-arrow-right"></i>'.'</a>';
            $output = apply_filters('infinity_blog_filter_read_more_link', $output);
        }
		
	}
	return $output;

}

add_filter('excerpt_more', 'infinity_blog_excerpt_more');
endif;

if (!function_exists('infinity_blog_get_link_url')):

/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since 1.0.0
 *
 * @return string The Link format URL.
 */
function infinity_blog_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content($content);

	return ($has_url)?$has_url:apply_filters('the_permalink', get_permalink());
}

endif;

if (!function_exists('infinity_blog_fonts_url')):

/**
 * Return fonts URL.
 *
 * @since 1.0.0
 * @return string Fonts URL.
 */
function infinity_blog_fonts_url() {
	$fonts_url = '';
	$fonts     = array();

	$infinity_blog_primary_font   = infinity_blog_get_option('primary_font');
	$infinity_blog_secondary_font = infinity_blog_get_option('secondary_font');

	$infinity_blog_fonts   = array();
	$infinity_blog_fonts[] = $infinity_blog_primary_font;
	$infinity_blog_fonts[] = $infinity_blog_secondary_font;

	$infinity_blog_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

	$i = 0;
	for ($i = 0; $i < count($infinity_blog_fonts); $i++) {

		if ('off' !== sprintf(_x('on', '%s font: on or off', 'infinity-blog'), $infinity_blog_fonts[$i])) {
			$fonts[] = $infinity_blog_fonts[$i];
		}

	}

	if ($fonts) {
		$fonts_url = add_query_arg(array(
				'family' => urldecode(implode('|', $fonts)),
			), 'https://fonts.googleapis.com/css');
	}

	return $fonts_url;
}

endif;

/*Recomended plugin*/
if (!function_exists('infinity_blog_recommended_plugins')):

/**
 * Recommended plugins
 *
 */
function infinity_blog_recommended_plugins() {
	$infinity_blog_plugins = array(
        array(
            'name'     => __('Social Share With Floating Bar', 'infinity-blog'),
            'slug'     => 'social-share-with-floating-bar',
            'required' => false,
        ),
        array(
            'name'      => esc_html__('Demo Import Kit','infinity-blog'),
            'slug'      => 'demo-import-kit',
            'required'  => false,
        ),

	);
	$infinity_blog_plugins_config = array(
		'dismissable' => true,
	);

	tgmpa($infinity_blog_plugins, $infinity_blog_plugins_config);
}
endif;
add_action('tgmpa_register', 'infinity_blog_recommended_plugins');


function infinity_blog_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}

add_filter( 'get_the_archive_title', 'infinity_blog_archive_title' );

if( class_exists( 'Booster_Extension_Class' ) ){

    add_filter('booster_extemsion_content_after_filter','infinity_blog_after_content_pagination');

}

if( !function_exists('infinity_blog_after_content_pagination') ):

    function infinity_blog_after_content_pagination($after_content){

        $pagination_single = wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'infinity-blog' ),
                    'after'  => '</div>',
                    'echo' => false
                ) );

        $after_content =  $pagination_single.$after_content;

        return $after_content;

    }

endif;

if( !function_exists('infinity_blog_post_floating_nav') ):

    function infinity_blog_post_floating_nav(){

        $infinity_blog_default = infinity_blog_get_default_theme_options();
        $ed_floating_next_previous_nav = get_theme_mod( 'ed_floating_next_previous_nav',$infinity_blog_default['ed_floating_next_previous_nav'] );

        if( 'post' === get_post_type() && $ed_floating_next_previous_nav ){

            $next_post = get_next_post();
            $prev_post = get_previous_post();

            if( isset( $prev_post->ID ) ){

                $prev_link = get_permalink( $prev_post->ID );?>

                <div class="floating-post-navigation floating-navigation-prev">
                    <?php if( get_the_post_thumbnail( $prev_post->ID,'medium' ) ){ ?>
                            <?php echo wp_kses_post( get_the_post_thumbnail( $prev_post->ID,'medium' ) ); ?>
                    <?php } ?>
                    <a href="<?php echo esc_url( $prev_link ); ?>">
                        <span class="floating-navigation-label"><?php echo esc_html__('Previous post', 'infinity-blog'); ?></span>
                        <span class="floating-navigation-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                    </a>
                </div>

            <?php }

            if( isset( $next_post->ID ) ){

                $next_link = get_permalink( $next_post->ID );?>

                <div class="floating-post-navigation floating-navigation-next">
                    <?php if( get_the_post_thumbnail( $next_post->ID,'medium' ) ){ ?>
                        <?php echo wp_kses_post( get_the_post_thumbnail( $next_post->ID,'medium' ) ); ?>
                    <?php } ?>
                    <a href="<?php echo esc_url( $next_link ); ?>">
                        <span class="floating-navigation-label"><?php echo esc_html__('Next post', 'infinity-blog'); ?></span>
                        <span class="floating-navigation-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                    </a>
                </div>

            <?php
            }

        }

    }

endif;

add_action( 'infinity_blog_navigation_action','infinity_blog_post_floating_nav',10 );
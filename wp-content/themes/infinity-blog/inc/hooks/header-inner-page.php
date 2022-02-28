<?php
global $post;
if (!function_exists('infinity_blog_single_page_title')) :
    function infinity_blog_single_page_title()
    {
        global $post;
        $global_banner_image = get_header_image();
        // Check if single.
        if (is_singular()) {
            if (has_post_thumbnail($post->ID)) {
                $banner_image_single_post = get_post_meta($post->ID, 'infinity-blog-meta-checkbox', true);
                if ($banner_image_single_post) {
                    $banner_image_array = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'infinity-blog-header-image');
                    $global_banner_image = $banner_image_array[0];
                }
            }
        }
        ?>
        <div class="hero">
             <?php  the_post_thumbnail('full',['class' => 'hero-img', 'title' => 'Feature image']); ?>
                <header class="header-single container has-img">
                        <div class="container">
                 <?php if (is_singular()) { ?>
                                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                                <div class="inner-meta-info">
                                    <?php if( is_singular('post') ){ infinity_blog_posted_details(); } ?>
                                </div>
                            <?php } elseif (is_404()) { ?>
                                <h1 class="entry-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'infinity-blog'); ?></h1>
                            <?php } elseif (is_archive()) {
                                the_archive_title('<h1 class="entry-title">', '</h1>'); ?>
                                <?php the_archive_description('<div class="taxonomy-description">', '</div>');
                            } elseif (is_search()) { ?>
                                <h1 class="entry-title"><?php printf(esc_html__('Search Results for: %s', 'infinity-blog'), '<span>' . get_search_query() . '</span>'); ?></h1>
                            <?php } else { } ?>
                <div class="meta right">
<!--    <p class="last-updated">Last Updated: <time class="published" datetime="2019-08-09T12:35:05-07:00">August 9, 2019</time></p>-->
    </div>
            </div>
            </header>
        </div>


        <?php
    }
endif;
add_action('infinity-blog-page-inner-title', 'infinity_blog_single_page_title', 15);

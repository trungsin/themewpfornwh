<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Infinity Blog
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while (have_posts()) : the_post(); ?>

                <?php
                $format = get_post_format();
                $format = (false === $format) ? 'single' : $format;
                ?>
                <?php get_template_part('template-parts/content', $format); ?>

                <?php
                // Previous/next post navigation.
                the_post_navigation(array(
                    'next_text' => '<span class="screen-reader-text">' . esc_html__('Next post:', 'infinity-blog') . '</span> ' .
                        '<span class="post-title">%title</span>',
                    'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous post:', 'infinity-blog') . '</span> ' .
                        '<span class="post-title">%title</span>',
                ));
               
                /**
                 * Navigation
                 * 
                 * @hooked infinity_blog_post_floating_nav - 10
                */

                do_action('infinity_blog_navigation_action');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_sidebar();
get_footer();

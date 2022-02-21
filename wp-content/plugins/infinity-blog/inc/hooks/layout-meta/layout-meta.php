<?php
/**
 * Implement theme metabox.
 *
 * @package Infinity Blog
 */

if (!function_exists('infinity_blog_add_theme_meta_box')) :

    /**
     * Add the Meta Box
     *
     * @since 1.0.0
     */
    function infinity_blog_add_theme_meta_box()
    {

        $apply_metabox_post_types = array('post', 'page');

        foreach ($apply_metabox_post_types as $key => $type) {
            add_meta_box(
                'infinity-blog-theme-settings',
                esc_html__('Single Page/Post Settings', 'infinity-blog'),
                'infinity_blog_render_theme_settings_metabox',
                $type
            );
        }

    }

endif;

add_action('add_meta_boxes', 'infinity_blog_add_theme_meta_box');

add_action( 'admin_enqueue_scripts', 'infinity_blog_backend_scripts');
if ( ! function_exists( 'infinity_blog_backend_scripts' ) ){
    function infinity_blog_backend_scripts( $hook ) {
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
    }
}

if (!function_exists('infinity_blog_render_theme_settings_metabox')) :

    /**
     * Render theme settings meta box.
     *
     * @since 1.0.0
     */
    function infinity_blog_render_theme_settings_metabox($post, $metabox)
    {

        $post_id = $post->ID;
        $infinity_blog_post_meta_value = get_post_meta($post_id);

        // Meta box nonce for verification.
        wp_nonce_field(basename(__FILE__), 'infinity_blog_meta_box_nonce');
        // Fetch Options list.
        $page_layout = get_post_meta($post_id, 'infinity-blog-meta-select-layout', true);
        $page_image_layout = get_post_meta($post_id, 'infinity-blog-meta-image-layout', true);
        $infinity_blog_meta_checkbox = get_post_meta($post_id, 'infinity-blog-meta-checkbox', true);
        ?>

        <div class="infinity-tab-main">

            <div class="infinity-metabox-tab">
                <ul>
                    <li>
                        <a id="twp-tab-general" class="twp-tab-active" href="javascript:void(0)"><?php esc_html_e('Layout Settings', 'infinity-blog'); ?></a>
                    </li>
                </ul>
            </div>

            <div class="infinity-tab-content">
                
                <div id="twp-tab-general-content" class="twp-content-wrap infinity-tab-content-active">

                    <div class="infinity-meta-panels">

                        <div class="infinity-opt-wrap twp-checkbox-wrap">

                            <input id="feature-image-checkbox" name="infinity-blog-meta-checkbox" type="checkbox" <?php if ($infinity_blog_meta_checkbox) { ?> checked="checked" <?php } ?> />

                            <label for="feature-image-checkbox"><?php esc_html_e('Check To Use Featured Image As Banner Image', 'infinity-blog'); ?></label>
                        </div>

                         <div class="infinity-opt-wrap infinity-opt-wrap-alt">
                            <label><?php esc_html_e('Single Page/Post Layout', 'infinity-blog'); ?></label>
                            <select name="infinity-blog-meta-select-layout" id="infinity-blog-meta-select-layout">
                                <option value="right-sidebar" <?php selected('right-sidebar', $page_layout); ?>>
                                    <?php esc_html_e('Content - Primary Sidebar', 'infinity-blog') ?>
                                </option>
                                <option value="left-sidebar" <?php selected('left-sidebar', $page_layout); ?>>
                                    <?php esc_html_e('Primary Sidebar - Content', 'infinity-blog') ?>
                                </option>
                                <option value="no-sidebar" <?php selected('no-sidebar', $page_layout); ?>>
                                    <?php esc_html_e('No Sidebar', 'infinity-blog') ?>
                                </option>
                            </select>
                        </div>

                        <div class="infinity-opt-wrap infinity-opt-wrap-alt">
                            <label><?php esc_html_e('Single Page/Post Image Layout', 'infinity-blog'); ?></label>
                            <select name="infinity-blog-meta-image-layout" id="infinity-blog-meta-image-layout">
                                <option value="full" <?php selected('full', $page_image_layout); ?>>
                                    <?php esc_html_e('Full', 'infinity-blog') ?>
                                </option>
                                <option value="left" <?php selected('left', $page_image_layout); ?>>
                                    <?php esc_html_e('Left', 'infinity-blog') ?>
                                </option>
                                <option value="right" <?php selected('right', $page_image_layout); ?>>
                                    <?php esc_html_e('Right', 'infinity-blog') ?>
                                </option>
                                <option value="no-image" <?php selected('no-image', $page_image_layout); ?>>
                                    <?php esc_html_e('No Image', 'infinity-blog') ?>
                                </option>
                            </select>
                        </div>


                    </div>
                </div>

            </div>
        </div>

        <?php
    }

endif;


if (!function_exists('infinity_blog_save_theme_settings_meta')) :

    /**
     * Save theme settings meta box value.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     */
    function infinity_blog_save_theme_settings_meta($post_id, $post)
    {

        // Verify nonce.
        if (!isset($_POST['infinity_blog_meta_box_nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['infinity_blog_meta_box_nonce'] ) ), basename(__FILE__))) {
            return;
        }

        // Bail if auto save or revision.
        if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if ( !isset($_POST['post_ID']) || empty($_POST['post_ID']) || sanitize_text_field( wp_unslash( $_POST['post_ID'] ) ) != $post_id) {
            return;
        }

        // Check permission.
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $infinity_blog_meta_checkbox = isset($_POST['infinity-blog-meta-checkbox']) ? sanitize_text_field( wp_unslash($_POST['infinity-blog-meta-checkbox'] ) ) : '';
        update_post_meta($post_id, 'infinity-blog-meta-checkbox', sanitize_text_field($infinity_blog_meta_checkbox));

        $infinity_blog_meta_select_layout = isset($_POST['infinity-blog-meta-select-layout']) ? sanitize_text_field( wp_unslash($_POST['infinity-blog-meta-select-layout'] ) ) : '';
        if (!empty($infinity_blog_meta_select_layout)) {
            update_post_meta($post_id, 'infinity-blog-meta-select-layout', sanitize_text_field($infinity_blog_meta_select_layout));
        }
        $infinity_blog_meta_image_layout = isset($_POST['infinity-blog-meta-image-layout']) ? sanitize_text_field( wp_unslash($_POST['infinity-blog-meta-image-layout'] ) ) : '';
        if (!empty($infinity_blog_meta_image_layout)) {
            update_post_meta($post_id, 'infinity-blog-meta-image-layout', sanitize_text_field($infinity_blog_meta_image_layout));
        }

    }

endif;

add_action('save_post', 'infinity_blog_save_theme_settings_meta', 10, 3);
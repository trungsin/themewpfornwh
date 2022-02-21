<?php
/*
Plugin Name: Slider Widget
Plugin URI: http://leesun.digital
Description: gh.
Author: Le Sin
Version: 1.0
Author URI: http://google.com
*/

/**
 * Tạo class Slider_Widget
 */
class Slider_Widget extends WP_Widget {
	
	/**
	 * Thiết lập widget: đặt tên, base ID
	 */
	function Slider_Widget() {
		$tpwidget_options = array(
			'classname' => 'slider_widget_class', //ID của widget
			'description' => 'Mô tả của widget'
		);
		$this->WP_Widget('slider_widget_id', 'Slider Widget', $tpwidget_options);
	}
	
	/**
	 * Tạo form option cho widget
	 */
	function form( $instance ) {
		
		//Biến tạo các giá trị mặc định trong form
		$default = array(
			'title' => 'Tiêu đề widget',
			'post_number' => 10
		);
		
		//Gộp các giá trị trong mảng $default vào biến $instance để nó trở thành các giá trị mặc định
		$instance = wp_parse_args( (array) $instance, $default);
		
		//Tạo biến riêng cho giá trị mặc định trong mảng $default
		$title = esc_attr( $instance['title'] );
		$post_number = esc_attr($instance['post_number']);
		
		//Hiển thị form trong option của widget
		//echo "<p>Nhập tiêu đề <input type='text' class='widefat' name='".$this->get_field_name('title')."' value='".$title."' /></p>";
		echo '<p>Nhập tiêu đề <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
        echo '<p>Số lượng bài viết hiển thị <input type="number" class="widefat" name="'.$this->get_field_name('post_number').'" value="'.$post_number.'" placeholder="'.$post_number.'" max="30" /></p>';
		
	}
	
	/**
	 * save widget form
	 */
	
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		return $instance;
	}
	
	/**
	 * Show widget
	 */
	
	function widget( $args, $instance ) {
		
		extract($args);
        $title = apply_filters('widget_title', $instance['title'] );
        $post_number = $instance['post_number'];


        echo $before_widget;
        echo $before_title.$title.$after_title;
        $random_query = new WP_Query('posts_per_page='.$post_number.'&orderby=rand');


        if ($random_query->have_posts()):
            echo "<ol>";
            while( $random_query->have_posts() ) :
                $random_query->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile;
            echo "</ol>";
        endif;
        echo $after_widget;
	}
	
}

/*
 * Khởi tạo widget item
 */
add_action( 'widgets_init', 'create_slider_widget' );
function create_slider_widget() {
	register_widget('Slider_Widget');
}
<?php 
// archive.php
get_header();
?>
<div class="container"> 
    <?php       
    if(is_tax()){ // Taxonomy Archives
        $queried_object = get_queried_object();
        echo '<h1>'.$queried_object->name.'</h1>';
    }   
    
    if(is_category()){ // Category Archives
        $cat = get_query_var('cat');
        $category = get_category ($cat);
        echo '<h1>'. $category->cat_name .'</h1>';
    }   
    
    if(is_tag()){  // Tag Archives
        $tag = get_query_var('tag'); 
        echo '<h1>'. single_tag_title('',false) .'</h1>';
    }   
    
    if(is_author()){ // Author Archives
        echo '<h1>'. get_the_author_meta('display_name') .'</h1>';  
    }   
    
    if(is_year()){ // Year Archives
        echo '<h1>'. the_date('Y') .'</h1>';
    }   
    
    if(is_month()){ // Month Archives
        echo '<h1>'. the_date('F, Y') .'</h1>';
    }   
    
    if(is_day()){ // Day Archives
        echo '<h1>'. the_date('F jS, Y') .'</h1>';
    }  
    ?>
    
    <?php echo do_shortcode('[ajax_load_more archive="true" post_type="post"]'); ?> 
    
</div>
<?php get_footer(); ?>
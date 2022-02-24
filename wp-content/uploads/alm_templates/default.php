<li class="alm-item<?php if (! has_post_thumbnail() ) { echo ' no-img'; } ?>">
   <?php if ( has_post_thumbnail() ) {
      the_post_thumbnail('alm-thumbnail');
   }?>
   <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
   <p class="entry-meta">
       dd<?php the_time("F d, Y"); ?>
   </p>
   <?php the_excerpt(); ?>
</li>
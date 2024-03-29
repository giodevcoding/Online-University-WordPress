<?php 
    
get_header();

page_banner( array(
    'title' => 'Our Blog',
    'subtitle' => 'Keep up with the latest news'
) );

?>


<div class="container container--narrow page-section">
    <?php 

    while( have_posts() ) {
        the_post();
        ?>

        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h2>

            <div class="metabox">
                <p>Posted by <?php echo get_the_author_posts_link() . " on " . get_the_time('F dS, Y') . " in " . get_the_category_list(', '); ?></p>
            </div>

            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a href="<?php the_permalink(); ?>" class="btn btn--blue">Continue Reading &raquo</a></p>
            </div>
        </div>

    <?php
    }

    echo paginate_links();
    ?>
</div>

<?php

get_footer();

?>
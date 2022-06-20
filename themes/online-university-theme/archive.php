<?php 
    
get_header();

$archive_title = get_the_archive_title();

if( is_category() ) {
   $archive_title = single_cat_title("Posts in ", false);
} else if( is_tag() ) {
    $archive_title = single_tag_title("Posts tagged ", false);
} elseif ( is_author() ) {
    $archive_title =  "Posts by " . get_the_author();
} elseif ( is_day() ){
    $archive_title =  "Posts from " . get_the_time('F jS, Y');
} elseif ( is_month() ){
    $archive_title =  "Posts from " . get_the_time('F, Y');
} elseif ( is_year() ){
    $archive_title = "Posts from " . get_the_time('Y');
} else {
    $archive_title =  "Archives";
}

page_banner( array(
    'title' => $archive_title,
    'subtitle' => get_the_archive_description()
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
                <p>Posted by <?php echo get_the_author_posts_link() . " on " . get_the_time( 'F dS, Y' ) . " in " . get_the_category_list( ', ' ); ?></p>
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
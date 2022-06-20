<?php

get_header();

page_banner();

while(have_posts()) {
    the_post(); 
    ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> 
                     Blog Home
                </a> 
                <span class="metabox__main">
                    Posted by <?php echo get_the_author_posts_link() . " on " . get_the_time('F dS, Y') . " in " . get_the_category_list(', '); ?>
                </span>
            </p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>
    </div>

    
    <?php
}

get_footer();

?>
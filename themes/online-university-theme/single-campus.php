<?php

get_header();

page_banner();

while(have_posts()) {
    the_post(); 
    ?>
    

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> 
                     All Campuses
                </a> 
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>
        <div class="acf-map">
            <?php 
            $map_location = get_field( 'map_location' );
            ?>
            <div class="marker" data-lat="<?php echo $map_location['lat']; ?>" data-lng="<?php echo $map_location['lng']; ?>">
                <h3><?php the_title();?> </h3>
                <?php echo $map_location['address']; ?>
            </div>
        </div>
 
        <?php

        $programs_query = new WP_Query( array(
            'post_type' => 'program',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ) );

        if( $programs_query->have_posts() ) {
            ?>

            <hr class="section-break">
            <h2 class="headline headline--medium">Programs Available At This Campus</h2>

            <ul class="min-list link-list">
            <?php
            while( $programs_query->have_posts() ) {
                $programs_query->the_post();
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </li>

                <?php
            }

            ?>
            </ul>
            <?php
        }

        wp_reset_postdata();
        ?>

    </div>

    
    <?php
}

get_footer();

?>
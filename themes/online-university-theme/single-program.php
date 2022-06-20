<?php

get_header();

page_banner();

while(have_posts()) {
    the_post(); 
    ?>
    

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> 
                     All Programs
                </a> 
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content"><?php the_field( 'main_body_content' ); ?></div>
 
        <?php

        $professors_query = new WP_Query( array(
            'post_type' => 'professor',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ) );

        if( $professors_query->have_posts() ) {
            ?>

            <hr class="section-break">
            <h2 class="headline headline--medium"><?php echo get_the_title(); ?> Professors</h2>

            <ul class="professor-cards">
            <?php

            while( $professors_query->have_posts() ) {
                $professors_query->the_post();
                ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url( 'professor-landscape' ); ?>" alt="" class="professor-card__image">
                        <span class="professor-card__name"> <?php the_title(); ?></span>
                    </a>
                </li>

                <?php
            }

            ?>
            </ul>
            <?php
        }

        wp_reset_postdata();

        $events_query = new WP_Query( array(
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array( 
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ) );

        if( $events_query->have_posts() ) {
            ?>

            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php echo get_the_title(); ?> Events</h2>

            <?php

            while( $events_query->have_posts() ) {
                $events_query->the_post();
                get_template_part('template-parts/content', 'event');
            }
        }

        wp_reset_postdata();

        $related_campuses = get_field('related_campus');

        if( $related_campuses ) {
            ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php the_title(); ?> is Avaialable at These Campuses:</h2>
            <ul class="min-list link-list">
                <?php
                foreach( $related_campuses as $campus ) {
                    ?>
                        <li><a href="<?php echo get_the_permalink( $campus ); ?>"><?php echo get_the_title( $campus ); ?></a></li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
        ?>

    </div>

    
    <?php
}

get_footer();

?>
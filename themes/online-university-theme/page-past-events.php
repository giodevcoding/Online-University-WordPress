<?php 
    
get_header();

page_banner( array(
    'title' => 'Past Events',
    'subtitle' => "Find out what's already happened!"
) );

?>


<div class="container container--narrow page-section">
    <?php 

    $today = date('Ymd');
    $past_events_query = new WP_Query( array(
        'post_type' => 'event',
        'paged' => get_query_var( 'paged', 1 ),
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array( 
                'key' => 'event_date',
                'compare' => '<',
                'value' => $today,
                'type' => 'numeric'
            )
        )
    ) );

    while( $past_events_query->have_posts() ) {
        $past_events_query->the_post();
        get_template_part('template-parts/content', 'event');
    }

    echo paginate_links( array(
        'total' => $past_events_query->max_num_pages
    ) );

    wp_reset_postdata();
    ?>
</div>

<?php

get_footer();

?>
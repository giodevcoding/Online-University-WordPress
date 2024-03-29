<?php

function related_posts_HTML( $id ) {
    $posts_about_prof = new WP_Query( array(
        'posts_per_page'    => -1,
        'post_type'         => 'post',
        'meta_query'        => array(
            array(
                'key'       => 'featuredprofessor',
                'compare'   => '=',
                'value'     => $id
            )
        )
    ) );

    ob_start();
    
    if( $posts_about_prof->found_posts ) {
        ?>
        <p><?php the_title(); ?> is mentioned in the following posts:</p>
        <ul>
            <?php
            while( $posts_about_prof->have_posts() ){
                $posts_about_prof->the_post();
                ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php
            }
            ?>
        </ul>
        <?php
    }

    wp_reset_postdata();
    return ob_get_clean();
}
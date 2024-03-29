<?php 
    
get_header();

page_banner( array(
    'title' => 'All Programs',
    'subtitle' => "There's something for everyone, so have a look around!"
) );

?>


<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php 

        while( have_posts() ) {
            the_post();
            $event_date = new DateTime( get_field( 'event_date' ) );
            ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php
        }

        echo paginate_links();
        ?>
    </ul>
    
</div>

<?php

get_footer();

?>
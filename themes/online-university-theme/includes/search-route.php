<?php 

    function university_register_search(){

        register_rest_route( 'university/v1', 'search', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'university_search_results'
        ) );

    }

    function university_search_results( $data ) {
        
        $main_query = new WP_Query( array(
            'post_type' => array( 'post', 'page', 'professor', 'program', 'event', 'campus' ),
            's' => sanitize_text_field( $data['term'] )
        ) );
        

        $results = array(
            'generalInfo'   => array(),
            'professors'    => array(),
            'programs'      => array(),
            'events'        => array(),
            'campuses'      => array()
        );

        while ( $main_query->have_posts() ) {
            $main_query->the_post();

            if ( get_post_type() == 'post' OR get_post_type() == 'page' ) {

                array_push( $results['generalInfo'], array(
                    'title'         => get_the_title(),
                    'permalink'     => get_the_permalink(),
                    'type'          => get_post_type(),
                    'author_name'   => get_the_author()
                ) );

            } elseif ( get_post_type() == 'professor' ) {

                array_push( $results['professors'], array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'imageURL'  => get_the_post_thumbnail_url( 0, 'professor-landscape' )
                ) );

            }  elseif ( get_post_type() == 'program' ) {

                $related_campuses = get_field( 'related_campus' );

                if ( $related_campuses ){
                    foreach ( $related_campuses as $campus ) {

                        array_push( $results['campuses'], array(
                            'title'     => get_the_title( $campus ),
                            'permalink' => get_the_permalink( $campus )
                        ) ); 

                    }
                }
               

                array_push( $results['programs'], array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id'        => get_the_ID()
                ) );

            } elseif ( get_post_type() == 'event' ) {

                $event_date = new DateTime( get_field( 'event_date' ) ); 
                $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 18 );

                array_push( $results['events'], array(
                    'title'         => get_the_title(),
                    'permalink'     => get_the_permalink(),
                    'month'         => $event_date->format( 'M' ),
                    'day'           => $event_date->format( 'j' ),
                    'description'   => $description
                ) );

            } elseif ( get_post_type() == 'campus' ) {

                array_push( $results['campuses'], array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink()
                ) );

            }

            
        }

        wp_reset_postdata();

        if ( $results['programs'] ) {

            $programs_meta_query = array( 'relation' => 'OR' );

            foreach ( $results['programs'] as $program ){
                
                array_push( $programs_meta_query, array(
                    'key'       => 'related_programs',
                    'compare'   => 'LIKE',
                    'value'     => '"'. $program['id'] .'"'
                ) );

            }

            $program_relationship_query = new WP_Query( array(
                'post_type'     => array( 'professor', 'event' ),
                'meta_query'    => $programs_meta_query
            ) );

            while ( $program_relationship_query->have_posts() ) {
                $program_relationship_query->the_post();

                if( get_post_type() == 'professor' ){

                    array_push( $results['professors'], array(
                        'title'     => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'imageURL'  => get_the_post_thumbnail_url( 0, 'professor-landscape' )
                    ) );

                } elseif ( get_post_type() == 'event' ){

                    $event_date = new DateTime( get_field( 'event_date' ) ); 
                    $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 18 );

                    array_push( $results['events'], array(
                        'title'         => get_the_title(),
                        'permalink'     => get_the_permalink(),
                        'month'         => $event_date->format( 'M' ),
                        'day'           => $event_date->format( 'j' ),
                        'description'   => $description
                    ) );

                }
               
            }

            wp_reset_postdata();
            
            
        }

        //Search for posts with author name

        $author_ids = array();

        $authors_list = get_users( array(
            'fields'    => array( 'ID', 'display_name' ),
            'role__in'  => array( 'author', 'editor', 'administrator' ),
            'orderby'   => 'display_name'
        ) );

        foreach ( $authors_list as $author ) {

            $author_name = strtolower( strval( $author->display_name ) );
            $search_term = strtolower( strval( $data['term'] ) );

            if( str_contains( $author_name, $search_term ) ) {
                array_push( $author_ids, $author->ID );
            }

        }

        $author_id_arg = -1;

        if ( $author_ids ) {
            $author_id_arg = implode( ',', $author_ids );
        }

        $posts_author_query = new WP_Query( array(
            'post_type' => 'post',
            'author' => $author_id_arg
        ) );


        while( $posts_author_query->have_posts() ){
            $posts_author_query->the_post();

            array_push( $results['generalInfo'], array(
                'title'         => get_the_title(),
                'permalink'     => get_the_permalink(),
                'type'          => get_post_type(),
                'author_name'   => get_the_author()
            ) );
            
        }

        wp_reset_postdata();

        foreach ( $results as $name => $arr ){
            $results[$name] = array_values( array_unique( $results[$name], SORT_REGULAR ) );
        }

        return $results;

    }

    add_action('rest_api_init', 'university_register_search');

?>
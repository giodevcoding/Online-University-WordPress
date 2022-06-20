<?php

add_action( 'rest_api_init', 'university_like_routes');
function university_like_routes(){
    register_rest_route('university/v1', 'manage-like', array(
        'methods' => 'POST',
        'callback' => 'add_like',
    ) );

    register_rest_route('university/v1', 'manage-like', array(
        'methods' => 'DELETE',
        'callback' => 'remove_like',
    ) );
}

function add_like( $data ) {
    if ( is_user_logged_in() ){
        $professor_id = sanitize_text_field( $data['professorID'] );

        $exist_query = new WP_Query( array(
            'author'        => get_current_user_id(),
            'post_type'     => 'like',
            'meta_query'    => array (
                array(
                    'key'       => 'liked_professor_id',
                    'compare'   => '=',
                    'value'     => $professor_id
                )
            )
        ) );

        if ( $exist_query->found_posts == 0 AND get_post_type( $professor_id ) == 'professor' ) {
            $post_data = array(
                'post_type'     => 'like',
                'post_status'   => 'publish',
                'post_title'    => 'Added Like',
                'meta_input'    => array(
                    'liked_professor_id'    => $professor_id
                )
            );
            return wp_insert_post( $post_data );
        } else {
            die("Invalid professor ID");
        }

       
    } else {
        die("Only logged in users can create a like.");
    }
}

function remove_like( $data ) {
    
    $likeID = sanitize_text_field( $data['postID'] );
    if ( get_current_user_id() == get_post_field( 'post_author', $likeID ) AND get_post_type( $likeID ) == 'like' ) {
        wp_delete_post( $likeID, true );
        return "Like Post with ID " . $likeID . " has been deleted.";
    } else {
        die(var_export($data));
    }
    
}
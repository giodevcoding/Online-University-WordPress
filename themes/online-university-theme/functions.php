<?php

require get_theme_file_path( '/includes/constants.php' ); 
require get_theme_file_path( '/includes/search-route.php' );
require get_theme_file_path( '/includes/like-route.php' );

add_action(  'wp_enqueue_scripts', 'university_files' );
function university_files() {
    wp_enqueue_style( 'google-fonts-roboto-condensed', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

    wp_enqueue_script( 'google-map', '//maps.googleapis.com/maps/api/js?key=AIzaSyALiv0mr1pxOYaNiDUv1FXKPnClJNcuEhA', null, '1.0', true );

    wp_enqueue_style( 'university_main_styles', get_theme_file_uri( '/build/style-index.css' ) );
    wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( '/build/index.css' ) );
    wp_enqueue_script( 'main-university-js', get_theme_file_uri( '/build/index.js' ), array( 'jquery' ), '1.0', true );


    wp_localize_script( 'main-university-js', 'universityData', array( 
        'root_url'  => get_site_url(),
        'nonce'     => wp_create_nonce( 'wp_rest' ),
        'noteLimit' => USER_NOTE_LIMIT
    ) );
}


add_filter( 'style_loader_tag', 'add_style_attributes', 10, 2 );
function add_style_attributes( $html, $handle ) {

    return $html;
}


add_filter( 'script_loader_tag', 'add_script_attributes', 10, 3 );
function add_script_attributes( $html, $handle, $src ) {

    return $html;
}


add_filter( 'acf/fields/google_map/api', 'university_map_key' );
function university_map_key( $api ) {
    $api['key'] = 'AIzaSyALiv0mr1pxOYaNiDUv1FXKPnClJNcuEhA';
    return $api;
}


add_action( 'after_setup_theme', 'university_features' );
function university_features() {
    register_nav_menus( array( 
        'header-menu'           => __( 'Header Menu' ),
        'footer-explore-menu'   => __( 'Footer Explore Menu' ),
        'footer-learn-menu'     => __( 'Footer Learn Menu' )
    ) );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'professor-landscape', 400, 260, true );
    add_image_size( 'professor-portrait', 480, 650, true );
    add_image_size( 'page-banner', 1500, 350, true );
}


function adjust_upcoming_event_archive_query( $query ) {

    if ( !is_admin() and is_post_type_archive( 'event' ) and $query->is_main_query() ) {

        $today = date( 'Ymd' );
        $query_vars = array( 
            'meta_key'      => 'event_date',
            'orderby'       => 'meta_value_num',
            'order'         => 'ASC',
            'meta_query'    => array( 
                array( 
                    'key'       => 'event_date',
                    'compare'   => '>=',
                    'value'     => $today,
                    'type'      => 'numeric'
                )
            )
        );

        foreach ( $query_vars as $key => $value ) {
            $query->set( $key, $value );
        }
    }
}


function adjust_programs_archive_query( $query ) {

    // Program Archive Query
    if ( !is_admin() and is_post_type_archive( 'program' ) and is_main_query() ) {

        $query_vars = array( 
            'orderby'           => 'title',
            'order'             => 'ASC',
            'posts_per_page'    => -1
         );

        foreach ( $query_vars as $key => $value ) {
            $query->set( $key, $value );
        }
    }

    // Campus Archive Query
    if ( !is_admin() and is_post_type_archive( 'campus' ) and is_main_query() ) {

        $query_vars = array( 
            'posts_per_page' => -1
         );

        foreach ( $query_vars as $key => $value ) {
            $query->set( $key, $value );
        }
    }
}


add_action( 'pre_get_posts', 'university_adjust_queries' );
function university_adjust_queries( $query ) {
    adjust_upcoming_event_archive_query( $query );
    adjust_programs_archive_query( $query );
}


add_action( 'rest_api_init', 'university_custom_rest' );
function university_custom_rest() {

    register_rest_field( 'post', 'authorName', array( 
        'get_callback' => function () {
            return get_the_author();
        }
    ) );

    register_rest_field( 'note', 'userNoteCount', array( 
        'get_callback' => function () {
            return count_user_posts( get_current_user_id(), 'note' );
        }
    ) );
}


//redirect subscribe out of admin page

add_action( 'admin_init', 'redirect_subscribers' );
function redirect_subscribers() {

    $current_user = wp_get_current_user();

    if ( count( $current_user->roles ) == 1 and $current_user->roles[0] == "subscriber" ) {
        wp_redirect( site_url( '/' ) );
        exit;
    }
}


add_action( 'wp_loaded', 'remove_subscriber_admin_bar' );
function remove_subscriber_admin_bar() {

    $current_user = wp_get_current_user();

    if ( count( $current_user->roles ) == 1 and $current_user->roles[0] == "subscriber" ) {
        show_admin_bar( false );
    }
}


add_filter( 'login_headerurl', 'header_url' );
function header_url() {
    return esc_url( site_url( '/' ) );
}


add_action( 'login_enqueue_scripts', 'login_css' );
function login_css() {
    wp_enqueue_style( 'google-fonts-roboto-condensed', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'university_main_styles', get_theme_file_uri( '/build/style-index.css' ) );
    wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( '/build/index.css' ) );
}


add_filter( 'login_headertitle', 'login_title' );
function login_title() {
    return '<strong>Online</strong> University';
}

// Force note posts to be private
add_filter( 'wp_insert_post_data', 'make_note_private', 10, 2 );
function make_note_private( $data, $post_arr ) {

    if ( $data['post_type'] == 'note' ) {

        $note_limit = USER_NOTE_LIMIT;

        if ( count_user_posts( get_current_user_id(), 'note' ) > $note_limit - 1 and ! $post_arr['ID'] ) {
            die( json_encode( array( 'message' => 'Note limit ( ' . $note_limit . ' ) reached!!', 'code' => 500 ) ) );
        }

        $data['post_title'] = sanitize_text_field( $data['post_title'] );
        $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
    }

    if ( $data['post_type'] == 'note' and $data['post_status'] != 'trash' ) {
        $data['post_status'] = 'private';
    }


    return $data;
}

function page_banner( $args = NULL ) {

    if ( ! $args['title'] ) {
        $args['title'] = get_the_title();
    }

    if ( ! $args['subtitle'] ) {
        $args['subtitle'] = get_field( 'page_banner_subtitle' );
    }

    if ( ! $args['image_url'] ) {
        if ( get_field( 'page_banner_background_image' ) && !is_archive() && !is_home() ) {
            $args['image_url'] = get_field( 'page_banner_background_image' )['sizes']['page-banner'];
        } else {
            $args['image_url'] = get_theme_file_uri( '/images/ocean.jpg' );
        }
    }

?>


    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url( <?php echo $args['image_url']; ?> )"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?
}
?>
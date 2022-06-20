<?php

add_action( 'init', 'banner_block' );
function banner_block() {

    wp_register_script( 'banner-block-script', get_stylesheet_directory_uri() . '/build/banner.js', array( 'wp-blocks', 'wp-editor' ) );
    register_block_type( "online-university-blocks/banner", array(
        'editor_script' => 'banner-block-script'
    ) );

}

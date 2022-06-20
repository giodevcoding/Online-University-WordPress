<?php

function university_post_types(){
    
    // Event Post Type
    register_post_type( 'event', array(
        'public'            => true,
        'labels'            => array( 
            'name'              => 'Events',
            'add_new_item'      => 'Create Event',
            'edit_item'         => 'Edit Event',
            'all_items'         => 'All Events',
            'singular_name'     => 'Event'
        ),
        'capability_type'   => 'event',
        'map_meta_cap'      => true,
        'supports'          => array( 'title', 'editor', 'excerpt', 'custom-fields'),
        'has_archive'       => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'events' ),
        'menu_icon'         => 'dashicons-calendar-alt'

    ) );

    // Program Post Type
    register_post_type( 'program', array(
        'public'        => true,
        'labels'        => array( 
            'name'          => 'Programs',
            'add_new_item'  => 'Create Program',
            'edit_item'     => 'Edit Program',
            'all_items'     => 'All Programs',
            'singular_name' => 'Program'
        ),
        'supports'      => array( 'title', 'custom-fields'),
        'has_archive'   => true,
        'show_in_rest'  => true,
        'rewrite'       => array( 'slug' => 'programs' ),
        'menu_icon'     => 'dashicons-awards'

    ) );

     // Professor Post Type
     register_post_type( 'professor', array(
        'public'        => true,
        'labels'        => array( 
            'name'          => 'Professors',
            'add_new_item'  => 'Create Professor',
            'edit_item'     => 'Edit Professor',
            'all_items'     => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-welcome-learn-more'

    ) );

    // Campus Post Type
    register_post_type( 'campus', array(
        'public'            => true,
        'labels'            => array( 
            'name'              => 'Campuses',
            'add_new_item'      => 'Create Campus',
            'edit_item'         => 'Edit Campus',
            'all_items'         => 'All Campuses',
            'singular_name'     => 'Campus'
        ),
        'capability_type'   => 'campus',
        'map_meta_cap'      => true,
        'supports'          => array( 'title', 'editor', 'custom-fields'),
        'has_archive'       => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'campuses' ),
        'menu_icon'         => 'dashicons-location-alt'

    ) );

    register_post_type( 'note', array(
        'public'            => false,
        'labels'            => array( 
            'name'              => 'Notes',
            'add_new_item'      => 'Create Note',
            'edit_item'         => 'Edit Note',
            'all_items'         => 'All Notes',
            'singular_name'     => 'Note'
        ),
        'show_ui'           => true,
        'show_in_rest'      => true,
        'capability_type'   => 'note',
        'map_meta_cap'      => true,
        'supports'          => array( 'title', 'editor' ),
        'menu_icon'         => 'dashicons-welcome-write-blog'

    ) );

    register_post_type( 'like', array(
        'public'            => false,
        'labels'            => array( 
            'name'              => 'Likes',
            'add_new_item'      => 'Create Like',
            'edit_item'         => 'Edit Like',
            'all_items'         => 'All Likes',
            'singular_name'     => 'Like'
        ),
        'show_ui'           => true,
        'supports'          => array( 'title' ),
        'menu_icon'         => 'dashicons-heart'

    ) );
}

add_action('init', 'university_post_types');

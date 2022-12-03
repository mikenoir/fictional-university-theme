<?php

function university_post_types() {
    // Event post type
    register_post_type('event', [
        'supports'      => [
            'title',
            'editor',
            'excerpt'
        ],
        'rewrite'       => [
            'slug'  => 'events'
        ],
        'has_archive'   => true,
        'public'        => true,
        'show_in_rest'  => true,
        'labels'    => [
            'name'          => 'Events',
            'add_new'       => 'Add New Event',
            'edit_item'     => 'Edit Event',
            'all_items'     => 'All Events',
            'singular_name' => 'Event'
        ],
        'menu_icon'     => 'dashicons-calendar'
    ]);

    // Campus post type
    register_post_type('campus', [
        'supports'      => [
            'title',
            'editor',
            'excerpt'
        ],
        'rewrite'       => [
            'slug'  => 'campus'
        ],
        'has_archive'   => true,
        'public'        => true,
        'show_in_rest'  => true,
        'labels'    => [
            'name'          => 'Campuses',
            'add_new'       => 'Add New Campus',
            'edit_item'     => 'Edit Campus',
            'all_items'     => 'All Campuses',
            'singular_name' => 'Campus'
        ],
        'menu_icon'     => 'dashicons-location-alt'
    ]);

    // Program post type
    register_post_type('program', [
        'supports'      => [
            'title',
            'editor'
        ],
        'rewrite'       => [
            'slug'  => 'programs'
        ],
        'has_archive'   => true,
        'public'        => true,
        'show_in_rest'  => true,
        'labels'    => [
            'name'          => 'Programs',
            'add_new'       => 'Add New Program',
            'edit_item'     => 'Edit Program',
            'all_items'     => 'All Programs',
            'singular_name' => 'Program'
        ],
        'menu_icon'     => 'dashicons-awards'
    ]);

    // Professor post type
    register_post_type('professor', [
        'supports'      => [
            'title',
            'editor',
            'thumbnail'
        ],
        'has_archive'   => false,
        'public'        => true,
        'show_in_rest'  => true,
        'labels'    => [
            'name'          => 'Professors',
            'add_new'       => 'Add New Professor',
            'edit_item'     => 'Edit Professor',
            'all_items'     => 'All Professors',
            'singular_name' => 'Professor'
        ],
        'menu_icon'     => 'dashicons-welcome-learn-more'
    ]);
}

add_action('init', 'university_post_types');
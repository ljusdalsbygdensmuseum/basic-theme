<?php
// init CPT

require 'post-type-functions/class-ljm_custom_post_type.php';
require 'post-type-functions/class-ljm_custom_metabox.php';
require 'post-type-functions/class-ljm_custom_field.php';
require 'post-type-functions/class-ljm_custom_column.php';

//file should be put in 'mu-plugins' in wp-content, so that a post type won't be deleted on theme change

// EVENT

$event_args = array(
    'post_type' => 'event',
    'prefix'    => 'ljm',
    'args'      => array(
        'rewrite'       => array('slug' =>'events'),
        'public'        => TRUE,
        'show_in_rest'  => TRUE,
        'has_archive'   => TRUE,
        'supports'      => array('title', 'editor', 'excerpt'),
        'labels'        => array(
            'name'          => 'Events',
            'add_new'       => 'Add Event',
            'add_new_item'  => 'Add Event',
            'edit_item'     => 'Edit Event',
            'singular_name' => 'Event'
        ),
        'menu_icon'     => 'dashicons-calendar-alt'
    ),
    'meta_box'  => array(
        array(
            'ID'    => 'date',
            'title' => 'Date',
            'meta_fields'   => array(
                array(
                    'key'   => 'dateof',
                    'label' => 'Date:',
                    'type'  => 'date',
                    'placeholder'   => 'YYYY-MM-DD',
                    'description'   => 'Date of event',
                    'restAPI'   => TRUE,
                ),
            ),
        ),
        array(
            'ID'    => 'related_programs',
            'title' => 'Related programs',
            'meta_fields'   => array(
                array(
                    'key'   => 'related_programs',
                    'type'  => 'post_relation',
                    'restAPI'   => TRUE,
                    'args'  => array(
                        'post_types'    => array('ljm_program')
                    )
                )
            )
        ),
    ),
    'columns'   => array(
        array(
            'name'  => 'Title',
            'ID'    => 'title'
        ),
        array(
            'name'  => 'Date',
            'ID'    => 'date_of',
            'values'    => array(
                array(
                    'value' => 'dateof',
                    'type'  => 'date'
                ),
            ),
            'format'    => '%dateof%',
            'sortable'  => TRUE,
            'orderby'   => 'dateof',
            'orderby_type'   => 'int',
        ), 
        array(
            'name'  => 'Related programs',
            'ID'    => 'relatedprograms',
            'values'    => array(
                array(
                    'value' => 'related_programs',
                    'type'  => 'post_relation'
                ),
            ),
            'format'    => '%related_programs%'
        ),
    )
);

new ljm_custom_post_type($event_args);

//--------------------------------------------------------------------------------------------------------------------------------------------
//PROGRAMS

$program_args = array(
    'post_type' => 'program',
    'prefix'    => 'ljm',
    'args'      => array(
        'rewrite'       => array('slug' =>'programs'),
        'public'        => TRUE,
        'show_in_rest'  => TRUE,
        'has_archive'   => TRUE,
        'supports'      => array('title', 'editor'),
        'labels'        => array(
            'name'          => 'Programs',
            'add_new'       => 'Add Program',
            'add_new_item'  => 'Add Program',
            'edit_item'     => 'Edit Program',
            'singular_name' => 'Program'
        ),
        'menu_icon'     => 'dashicons-book'
    ),
);

new ljm_custom_post_type($program_args);

//--------------------------------------------------------------------------------------------------------------------------------------------
//PROFESSORS

$meta_args = array(
    'post_type' => 'professors',
    'prefix'    => 'ljm',
    'args' => array(
        'public'        => TRUE,
        'show_in_rest'  => TRUE,
        'supports'      => array('title', 'editor', 'excerpt', 'thumbnail'),
        'labels'        => array(
            'name'          => 'Professors',
            'add_new'       => 'Add Professors',
            'add_new_item'  => 'Add Professors',
            'edit_item'     => 'Edit Professors',
            'singular_name' => 'Professors'
        ),
        'menu_icon'     => 'dashicons-buddicons-activity'
    ),
    'meta_box' => array(
        array(
            'ID'    => 'related_programs',
            'title' => 'Related programs',
            'meta_fields'   => array(
                array(
                    'key'   => 'related_programs',
                    'type'  => 'post_relation',
                    'restAPI'   => TRUE,
                    'args'  => array(
                        'post_types'    => array('ljm_program')
                    )
                )
            )
        ),
    ),
    'columns'   => array(
        array(
            'name'  => 'Title',
            'ID'    => 'title'
        ),
        array(
            'name'  => 'Related programs',
            'ID'    => 'relatedprograms',
            'values'    => array(
                array(
                    'value' => 'related_programs',
                    'type'  => 'post_relation'
                ),
            ),
            'format'    => '%related_programs%'
        ), 
    )
);


new ljm_custom_post_type($meta_args);

//--------------------------------------------------------------------------------------------------------------------------------------------
//CAMPUS

$campus_args = array(
    'post_type' => 'campus',
    'prefix'    => 'ljm',
    'args' => array(
        'public'        => TRUE,
        'show_in_rest'  => TRUE,
        'has_archive'   => TRUE,
        'supports'      => array('title', 'editor', 'excerpt', 'thumbnail'),
        'labels'        => array(
            'name'          => 'Campuses',
            'add_new'       => 'Add campus',
            'add_new_item'  => 'Add campus',
            'edit_item'     => 'Edit campus',
            'singular_name' => 'Campuses'
        ),
        'menu_icon'     => 'dashicons-store'
    ),
    'meta_box'  => array(
        array(
            'ID'    => 'location',
            'title' => 'Location',
            'meta_fields'   => array(
                array(
                    'key'   => 'location',
                    'type'  => 'map',
                )
            ),
        ),
    ),
);


new ljm_custom_post_type($campus_args);


//--------------------------------------------------------------------------------------------------------------------------------------------
//NOTES

$campus_args = array(
    'post_type' => 'note',
    'prefix'    => 'ljm',
    'args' => array(
        'public'        => false, // ---------------makes so that search queries wont accedentaly grab them
        'show_ui'       => true, // ---------------- makes a non-public cpt visible in the admin
        'show_in_rest'  => TRUE,
        'capability_type' => 'note',
        'map_meta_cap'  => true,
        'supports'      => array('title', 'editor'),
        'labels'        => array(
            'name'          => 'Notes',
            'add_new'       => 'Add note',
            'add_new_item'  => 'Add note',
            'edit_item'     => 'Edit note',
            'singular_name' => 'Note'
        ),
        'menu_icon'     => 'dashicons-format-quote'
    ),
);


new ljm_custom_post_type($campus_args);


//--------------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------------
// metaboxes for existing post types

$meta_box_all = array(
    'post_type' => array('post', 'page', 'ljm_event', 'ljm_program', 'ljm_professors', 'ljm_campus'),
    'meta_box'  => array(
        array(
            'ID'    => 'page_banner',
            'title' => 'Page banner',
            'position'  => 'side',
            'general'   => TRUE,
            'meta_fields'   => array(
                array(
                    'key'   => 'page_banner_text',
                    'label' => 'text:',
                    'type'  => 'text',
                    'restAPI'   => TRUE,
                ),
                array(
                    'key'   => 'page_banner_img',
                    'type'  => 'image',
                    'restAPI'   => TRUE,
                )
            ),
        ),
    ),
    
);

foreach ($meta_box_all['post_type'] as $post_type) {
    foreach ($meta_box_all['meta_box'] as $meta_box) {
        new ljm_custom_metabox($post_type, $meta_box);
    }
}



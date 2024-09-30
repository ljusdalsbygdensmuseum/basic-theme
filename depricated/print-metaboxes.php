<?php
// init metaboxes

/* 
notes:
- change to a class for optimizing
- make a required field
- print the right clases for each elem acording to wordpress standard
- select related posts, from sertain post type
*/


function print_new_metabox(array $boxes, array $args){
    $default = array(
        'post-type'     => 'post',
        'id'            => '',
        'position'      => 'side',
        'column'        => array(),
        'sortable'      => FALSE,
        'orderby'       => FALSE,
        'title'         => '',
        'beg'           => '',
        'end'           => '',
    );
    // Pushing default if option empty
    $args = wp_parse_args($args, $default);

    // Fire ljm_custom_metabox and passing variables throug an anon function
    add_action('add_meta_boxes', function() use ($boxes, $args){
        ljm_custom_metabox($args, $boxes);
    });

    // Fire ljm_save_meta_values and passing variables throug an anon function
    add_action('save_post', function() use ($boxes, $args){
        ljm_save_meta_values($args, $boxes);
    });

    // Fire ljm_in_restAPI and passing variables throug an anon function
    add_action('rest_api_init', function() use ($boxes, $args){
        ljm_in_restAPI($args, $boxes);
    });

    // Check if have custom columns
    if ($args['column']) {
        // Init columns
        add_filter('manage_'.$args['post-type'].'_posts_columns', function() use($args) {
            $new_columns = array();
            foreach ($args['column'] as $column) {
                $new_columns[$column['id']] =  $column['name'];
            }
            return $new_columns;
        });
        add_action('manage_'.$args['post-type'].'_posts_custom_column', function() use($args){
            ljm_custom_column($args);
        });

        // Sortable columns
        foreach ($args['column'] as $column) {
            //check if has sortable columns
            if (!isset($column['sortable']) || !$column['sortable']) {
                continue;
            }
            // Setting columns as sortable
            add_filter('manage_edit-'.$args['post-type'].'_sortable_columns', function ($columns) use($column) {
                $columns[$column['id']] = $column['id'];
                return $columns;
            }, 10, 2);
            add_action('pre_get_posts', function($query) use( $column, $args){
                ljm_custom_column_orderby($query, $column, $args);
                
            }, 10, 3);
            
        }
        

    }
}

function ljm_custom_metabox($args, $boxes) {
    $boxID = $args['post-type'].'_'.$args['id'];

    // Fire add_meta_box and passing variables throug an anon function
    add_meta_box($boxID, $args['id'], function() use ($boxes, $args) {
        ljm_metabox_content_calback($boxes, $args);
    }, $args['post-type'], $args['position']);
}

function ljm_metabox_content_calback($boxes, $args) {
    // Printing begining string
    echo $args['beg'];

    // Printing each
    foreach ($boxes as $box) {
        // Prints id for key and field
        $id = $args['post-type'].'_'.$box->key;

        // Grabs prev value and sanitize
        if ($box->type == 'text-area' || $box->type == 'textarea') {
            $value = sanitize_textarea_field(get_post_meta(get_the_ID(), '_'.$id.'_key', TRUE));

        }elseif ($box->type == 'number') {
            $value = (int) sanitize_text_field(get_post_meta(get_the_ID(), '_'.$id.'_key', TRUE));

        }elseif ($box->type == 'date') {
            $value = sanitize_text_field(get_post_meta(get_the_ID(), '_'.$id.'_key', TRUE));
            $value = preg_replace('/([^0-9\-])/', '', $value);

        }else{
            $value = sanitize_text_field(get_post_meta(get_the_ID(), '_'.$id.'_key', TRUE));
        }

        // Make nonce
        wp_nonce_field( 'ljm_save_meta_values', $id.'_nonce');

        // Checks if has label or description
        $lable ='';
        if (!empty($box->label)) {
            $label = '<label for="'.$id.'_field">'.$box->label.'</label>';
        }

        $decription ='';
        if (!empty($box->label)) {
            $decription = '<p class="descripion">'.$box->description.'</p>';
        }

        // Prints the fields
        if ($box->type == 'text-area' || $box->type == 'textarea') {
            $field = '<textarea id="'.$id.'_field" name="'.$id.'_field" placeholder="'.$box->placeholder.'">'.$value.'</textarea>';
        }elseif ($box->type == 'number') {
            $field = '<input type="number" id="'.$id.'_field" name="'.$id.'_field" placeholder="'.$box->placeholder.'" value="'.$value.'">';

        }elseif ($box->type == 'date') {
            if ($value) {
                $value = substr_replace($value, '-', 4, 0 );
                $value = substr_replace($value, '-', 7, 0 );
            }
            $field = '<input type="text" id="'.$id.'_field" name="'.$id.'_field" class="jquery-ui-datepicker" placeholder="'.$box->placeholder.'" value="'.$value.'">';
        }else{
            $field = '<input type="text" id="'.$id.'_field" name="'.$id.'_field" placeholder="'.$box->placeholder.'" value="'.$value.'">';
        }

        echo $label.$field.$decription;

        
    }
    // Printing end string
    echo $args['end'];
}

function ljm_save_meta_values($args, $boxes) {
    // Saving each value
    foreach ($boxes as $box) {
        // Prints id
        $id = $args['post-type'].'_'.$box->key;

        // Validate and check nonce
        if ( ! isset( $_POST[$id.'_nonce'] ) ) {
            return;
        }
    
        if ( ! wp_verify_nonce( $_POST[$id.'_nonce'], 'ljm_save_meta_values' ) ) {
            return;
        }
    
        // check if allowed to edit post
        if ( ! current_user_can( 'edit_post', get_the_ID() ) ) {
            return;
        }

        // Saves to post meta
        if (isset($_POST[$id.'_field'])) {
                // Grabs value and sanitize
            if ($box->type == 'text-area' || $box->type == 'textarea') {
                $value = sanitize_textarea_field($_POST[$id.'_field']);

            }elseif ($box->type == 'number') {
                $value = (int) sanitize_text_field($_POST[$id.'_field']);

            }elseif ($box->type == 'date') {
                $value = sanitize_text_field($_POST[$id.'_field']);
                $value = (int) preg_replace('/([^0-9])/', '', $value);

            }else{
                $value = sanitize_text_field($_POST[$id.'_field']);
            }

            // Updates meta
            update_post_meta(get_the_ID(), '_'.$id.'_key', $value);
        }
    }
}

function ljm_in_restAPI($args, $boxes){
    foreach ($boxes as $box) {
        // Prints id
        $key = '_'.$args['post-type'].'_'.$box->key.'_key';

        // Check if visible in Rest
        if (!$box->restAPI) {
            continue;
        }

        // Registers in Rest
        register_rest_field($args['post-type'], $key, array(
            'get_callback'  => function() use($key){
                return get_post_meta(get_the_ID(), $key, TRUE);
            },
        ));
    }
    
}

function ljm_custom_column($args){
    foreach ($args['column'] as $column) {
        // Check if it has values to print
        if (isset($column['values'])) {
            // Check if there are more than one value
            if (sizeof($column['values']) > 1) {
                $formated_value = $column['format'];
                foreach ($column['values'] as $value) {
                    // Grab meta data
                    $meta_data = get_post_meta(get_the_ID(), '_'.$args['post-type'].'_'.$value.'_key', TRUE);
                    // add - if mata data is date
                    if (strtoupper($column['orderby-type']) == 'DATE') {
                        $meta_data = substr_replace($meta_data, '-', 4, 0 );
                        $meta_data = substr_replace($meta_data, '-', 7, 0 );
                    }
                    $formated_value = str_replace('%'.$value.'%', $meta_data, $formated_value);
                }
            }else {
                $meta_data = get_post_meta(get_the_ID(), '_'.$args['post-type'].'_'.$column['values'][0].'_key', TRUE);
                // add - if mata data is date
                if (strtoupper($column['orderby-type']) == 'DATE') {
                    $meta_data = substr_replace($meta_data, '-', 4, 0 );
                    $meta_data = substr_replace($meta_data, '-', 7, 0 );
                }
                $formated_value = $meta_data;
            }

            echo $formated_value;
            
            continue;
        }else {
            continue;
        }
    }
}

function ljm_custom_column_orderby($query, $column, $args){
    // checking if allowed to be here
    if (!is_admin()) {
        return;
    }

    // Setting up the Query
    $key = '_'.$args['post-type'].'_'.$column['orderby'].'_key';
    if( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) && is_admin()) && $query->get('post_type') == $args['post-type'] ){
        if($orderby == $column['id']){

            $query->set('meta_key', $key);
            // Check if integer
            if (is_int(get_post_meta(get_the_ID(), $key))) {
                $query->set('orderby','meta_value_num');
            }
            else {
                $query->set('orderby','meta_value');
                //$query->set('meta-type', $column['orderby-type']);
            }
        }
    }   
}
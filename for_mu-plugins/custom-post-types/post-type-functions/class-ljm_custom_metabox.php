<?php

//metaboxes class
class ljm_custom_metabox 
{
    public string $post_type;
    public array $meta_general;
    public array $meta_fields;
    public function __construct($post_type, $meta_args){
        //Extracting and setting the arguments
        $this->post_type = $post_type;

        $this->meta_general = $meta_args;
        unset($this->meta_general['meta_fields']);

        if (isset($meta_args['meta_fields'])) {
            $this->meta_fields = $meta_args['meta_fields'];
        }
        
        // Printing the box
        $this->print_metabox();
        
    }
    public function print_metabox(){
        //Add meta box
        add_action('add_meta_boxes', array($this, 'setup_metabox'));

        //Save meta values
        add_action('save_post_'.$this->post_type , array($this, 'save_metavalues'));

        //RestAPI
        add_action('rest_api_init', array($this, 'restapi_metavalues'));

          
    }
    public function setup_metabox(){
        //ID of metabox
        $boxID = $this->post_type.'_'.$this->meta_general['ID'];

        //Check if position is stated otherwise fallback
        if (!isset($this->meta_general['position']) || empty($this->meta_general['position'])) {
            $this->meta_general['position'] = 'normal';
        }

        //Add meta box
        add_meta_box($boxID, $this->meta_general['title'], array($this, 'content_metabox'), $this->post_type, $this->meta_general['position']);
    
    }
    public function content_metabox($post){
        //Beginning and end text
        $beginning = '';
        if (isset($this->meta_general['beg_text']) && !empty($this->meta_general['beg_text'])) {
            $beginning = $this->meta_general['beg_text'].'</br>';
        }
        $end = '';
        if (isset($this->meta_general['end_text']) && !empty($this->meta_general['end_text'])) {
            $end = $this->meta_general['end_text'];
        }

        //Printing the fields
        $printed_field = '';

        if (isset($this->meta_fields) && !empty($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                //Key for selecting the field and value
                $key = $this->post_type.'_'.$meta_field['key'];

                //Key for general fields
                if (isset($this->meta_general['general']) && $this->meta_general['general'] == TRUE) {
                    $key = $meta_field['key'];
                }

                //Make nonce
                wp_nonce_field('save_metavalues', $key.'_nonce');

                //Check if type is specified
                if(!isset($meta_field['type']) || empty($meta_field['type'])){
                    $meta_field['type'] = '';
                }

                //Check if placeholder is specified
                if(!isset($meta_field['placeholder']) || empty($meta_field['placeholder'])){
                    $meta_field['placeholder'] = '';
                }
                
                //Check if arguments are specified
                if(!isset($meta_field['args']) || empty($meta_field['args'])){
                    $meta_field['args'] = [];
                }

                //Prints label
                if (isset($meta_field['label']) && !empty($meta_field['label'])) {
                    $printed_field .= '<label class="metabox-label" for="'.$key.'">'.$meta_field['label'].'</label> </br>';
                }else{
                    $meta_field['label'] = '';
                }

                //Prints fields
                $field = new ljm_custom_field($post->ID, $key, $meta_field['type'], $meta_field['label'], $meta_field['args'], $meta_field['placeholder']);
                $printed_field.= $field->get_the_field();

                //Prints description
                if (isset($meta_field['description']) && !empty($meta_field['description'])) {
                    $printed_field .= '<p class="metabox-description">'.$meta_field['description'].'</p>';
                }
            }
        }
        echo $beginning.$printed_field.$end;   

    }
    public function save_metavalues($id){
        if (isset($this->meta_fields) && !empty($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                //Key for selecting the field and value
                $key = $this->post_type.'_'.$meta_field['key'];

                //Key for general fields
                if (isset($this->meta_general['general']) && $this->meta_general['general'] == TRUE) {
                    $key = $meta_field['key'];
                }

                //Check and validate nonce
                if (!isset($_POST[$key.'_nonce'])) {
                    return;
                }

                if (!wp_verify_nonce($_POST[$key.'_nonce'], 'save_metavalues')) {
                    return;
                }

                //Check if user exists
                if (!wp_get_current_user()) {
                    return;
                }
                
                //Check if current user can edit post
                if (!current_user_can('edit_post', $id)) {
                    return;
                }

                //Check if the field exist
                if (!isset($_POST[$key]) && $meta_field['type'] != 'check') {
                    return;
                }

                //Grabs value
                $value = $_POST[$key];

                //Changes value if checkbox
                if ($meta_field['type'] == 'check' && !isset($_POST[$key])) {
                    $value = 0;
                }

                //Formating relations
                if ($meta_field['type'] == 'post_relation') {
                    array_pop($value);
                    for ($i=0; $i < count($value); $i++) { 
                        $value[$i] = '"'.$value[$i].'"';
                    }
                    $value = implode(', ', $value);
                }

                //Sanitizing value
                if ($meta_field['type'] == 'text-area' || $meta_field['type'] == 'text_area' || $meta_field['type'] == 'textarea') {
                    $value = sanitize_textarea_field($value);

                }elseif ($meta_field['type'] == 'number') {
                    $value = $value = (int) sanitize_text_field($value);

                }elseif ($meta_field['type'] == 'date') {
                    $value = sanitize_text_field($value);
                    $value = (int) preg_replace('/([^0-9])/', '', $value);
                }else {
                    $value = sanitize_text_field($value);
                }

                //Updating meta data
                update_post_meta($id, $key, $value);
            }
        }  
    }
    public function restapi_metavalues(){
        if (isset($this->meta_fields) && !empty($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                if (isset($meta_field['restAPI']) && $meta_field['restAPI'] == TRUE) {
                    //Key for selecting the field and value
                    $key = $this->post_type.'_'.$meta_field['key'];

                    //Key for general fields
                    if (isset($this->meta_general['general']) && $this->meta_general['general'] == TRUE) {
                        $key = $meta_field['key'];
                    }
    
                    //Register in restAPI
                    register_rest_field($this->post_type, $key, array(
                        'get_callback' => function($post, $key) {
                            return get_post_meta($post['id'], $key, TRUE);
                        },
                    ));
                }
            }
        }
    }
}

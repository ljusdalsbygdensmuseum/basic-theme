<?php
//metaboxes class
class custom_metabox_simple
{
    public array $meta_general;
    public array $meta_fields;
    public function __construct(array $meta_args)
    {
        //Extracting and setting the arguments
        $this->meta_general = $meta_args;
        unset($this->meta_general['meta-fields']);

        if (isset($meta_args['meta-fields'])) {
            $this->meta_fields = $meta_args['meta-fields'];
        }
        

        // Printing the box
        $this->print_metabox();
        
    }
    public function print_metabox(){
        //Add meta box
        add_action('add_meta_boxes', array($this, 'setup_metabox'));

        //Save meta values
        add_action('save_post_'.$this->meta_general['post-type'] , array($this, 'save_metavalues'));
    }
    public function setup_metabox(){
        //ID of metabox
        $boxID = $this->meta_general['post-type'].'_'.$this->meta_general['id'];

        //Add meta box
        add_meta_box($boxID, $this->meta_general['title'], array($this, 'content_metabox'), $this->meta_general['post-type'], $this->meta_general['position']);
    
    }
    public function content_metabox($post){
            //Key for selecting the field and value
            $key = 'ljm_'.$this->meta_general['post-type'].'_'.$this->meta_fields[0]['key'];

            //gets value
            $value = get_post_meta($post->ID, $key, TRUE);

            // Prints the fields
            $printed_field = '<input type="text" id="'.$key.'" name="'.$key.'" placeholder="'.$this->meta_fields[0]['placeholder'].'" value="'.$value.'">';
            echo $printed_field;
            
    }
    public function save_metavalues($id){
        $key = 'ljm_'.$this->meta_general['post-type'].'_'.$this->meta_fields[0]['key'];
        $value = $_POST[$key];
        update_post_meta($id, $key, $value);
        
    }
    private function restapi_metavalues(){

    }
    private function print_column(){

    }
    private function sortable_column(){

    }
    private function orderby_column(){

    }

}
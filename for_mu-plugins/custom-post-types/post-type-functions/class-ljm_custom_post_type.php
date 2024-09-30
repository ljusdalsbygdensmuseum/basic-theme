<?php
//Custom post type class
class ljm_custom_post_type
{
    public string $post_type;
    public array $post_type_args;
    public array $metabox_args;
    public array $columns_args;
    public function __construct(array $post_type_args){
        //Checking for invalid array
        if (!isset($post_type_args['post_type']) || empty($post_type_args['post_type'])) {
            return 'No post type found';
        }

        //Extracting and setting the arguments
        if (!isset($post_type_args['prefix']) || empty($post_type_args['prefix'])) {
            $post_type_args['prefix'] = 'custom';
        }
        $this->post_type = $post_type_args['prefix'].'_'.$post_type_args['post_type'];

        if (isset($post_type_args['args']) && !empty($post_type_args['args'])) {
            $this->post_type_args = $post_type_args['args'];
        }

        if (isset($post_type_args['meta_box']) && !empty($post_type_args['meta_box'])) {
            $this->metabox_args = $post_type_args['meta_box'];
        }
        
        if (isset($post_type_args['columns']) && !empty($post_type_args['columns'])) {
            $this->columns_args = $post_type_args['columns'];
        }

        //initializing post type
        add_action('init', array($this, 'init_post_type'));
        
        //Checking and adding meta boxes
        if (isset($this->metabox_args) && !empty($this->metabox_args)) {
            foreach ($this->metabox_args as $metabox) {
                if (isset($metabox['ID']) && !empty($metabox['ID'])) {
                    new ljm_custom_metabox($this->post_type, $metabox);
                    
                }
            }
        }
        
        //Checking and adding custom columns
        if (isset($this->columns_args) && !empty($this->columns_args)) {
            new ljm_custom_column($this->post_type, $this->columns_args);        
        }


    }
    public function init_post_type() {
        register_post_type($this->post_type, $this->post_type_args);
    }
}

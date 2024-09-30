<?php
//Custom fields class
class ljm_custom_field
{
    public string $postID;
    public string $key;
    public string $label;
    public array $args;
    public string $placeholder;
    public string $printed_field = '';
    public function __construct(string $postID, string $key, string $type, string $label, array $args = array(), string $placeholder = ''){
        //Extracting and setting the arguments
        $this->postID = $postID;
        $this->key = $key;
        $this->label = $label;
        $this->args = $args;
        $this->placeholder = $placeholder;

        if ($type == 'text') {
            $this->type_text();

        }elseif ($type == 'textarea' || $type == 'text-area' || $type == 'text_area') {
            $this->type_textarea();

        }elseif ($type == 'number') {
            $this->type_number();

        }elseif ($type == 'date') {
            $this->type_date();

        }elseif ($type == 'radio') {
            $this->type_radio();

        }elseif ($type == 'check') {
            $this->type_checkbox();

        }elseif($type == 'image') {
            $this->type_image();

        }elseif ($type == 'post_relation') {
            $this->type_post_relation();
        }elseif ($type == 'map') {
            $this->type_map();
        }else {
            $this->type_text();
        }
        

    }
    public function get_the_field() {
        return $this->printed_field;
    }
    public function type_text() {
        //Grab and sanitize value
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));

        //Print field
        $field = '<input class="metabox-field" type="text" id="'.$this->key.'" name="'.$this->key.'" placeholder="'.$this->placeholder.'" value="'.$value.'">';
        $this->printed_field = $field;
    }
    public function type_textarea() {
        //Grab and sanitize value
        $value = sanitize_textarea_field(get_post_meta($this->postID, $this->key, TRUE));

        //Print field
        $field = '<textarea class="metabox-field" type="text" id="'.$this->key.'" name="'.$this->key.'" placeholder="'.$this->placeholder.'">'.$value.'</textarea>';
        $this->printed_field = $field;
    }
    public function type_number() {
        //Grab and sanitize value
        $value = (int) sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));

        //Print field
        $field = '<input class="metabox-field" type="number" id="'.$this->key.'" name="'.$this->key.'" placeholder="'.$this->placeholder.'" value="'.$value.'">';
        $this->printed_field = $field;
    }
    public function type_date() {
        //Grab and sanitize value
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));
        $value = preg_replace('/([^0-9\-])/', '', $value);

        //Formats value
        if (!empty($value)) {
            $value = substr_replace($value, '-', 4, 0 );
            $value = substr_replace($value, '-', 7, 0 );
        }

        //Print field
        $field = '<input class="metabox-field jquery-ui-datepicker" type="text" id="'.$this->key.'" name="'.$this->key.'" placeholder="'.$this->placeholder.'" value="'.$value.'">';
        $this->printed_field = $field;
    }
    public function type_radio() {
        //Grab and sanitize value
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));

        //Check if arguments exists and setting arguments
        if(!isset($this->args['options']) || empty($this->args['options'])){
            return 'No Options stated';
        }
        $options = $this->args['options'];

        //Printing each option with label
        foreach ($options as $option) {
            //Check if selected
            if ($value == $option['id']) {
                $checked = 'checked';
            }else{
                $checked = '';
            }

            //Print field
            $this->printed_field .= '<input type="radio" id='.$this->key.'_'.$option['id'].' name="'.$this->key.'" value="'.$option['id'].'" '.$checked.'>';
            //Print label
            $this->printed_field .= '<label class="name_label_editor" for="'.$this->key.'_'.$option['id'].'">'.$option['label'].'</label></br>';
        }
    }
    public function type_checkbox() {
        //Grab and sanitize value
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));
        
        //Check if selected
        if ($value == 1) {
            $checked = 'checked';
        }else{
            $checked = '';
        }

        //Print field
        $this->printed_field = '<input type="checkbox" id="'.$this->key.'" name="'.$this->key.'" value="1" '.$checked.'>';
        //Print label
        $this->printed_field .= '<label class="name_label_editor" for="'.$this->key.'">'.$this->args['label'].'</label></br>';
    }
    public function type_image() {
        //Grab and sanitize value
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));

        //Prints uppload button
        $this->printed_field .= '<img id="'.$this->key.'-display" src="'.wp_get_attachment_image_url($value, 'thumbnail').'">';
        $this->printed_field .= '<button class="custom-image-upload" data-key="'.$this->key.'">Upload image</button>';
        $this->printed_field .= '<input type="hidden" id="'.$this->key.'" name="'.$this->key.'" value="'.$value.'">';
    }
    public function type_post_relation() {
        //Grab and sanitize values and explodes into an array
        $values = explode(', ', sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE)));// change explode to :;:
        

        //Check if argument exist and setting arguments
        if(!isset($this->args['post_types']) || empty($this->args['post_types'])){
            $this->args['post_types'] = array('post');
        }

        //Query for each post type
        foreach ($this->args['post_types'] as $post_type) {
            $relations = new WP_Query(array(
                'posts_per_paged' => -1,
                'post_type' => $post_type,
                'orderby'   => 'post_title',
                'order'     => 'ASC',
            ));

            //Grabs and prints title
            $post_type_name = get_post_type_object($post_type)->labels->singular_name;

            $this->printed_field .= '<label class="sub_label_editor">'.$post_type_name.'</label></br>';
    
            while($relations->have_posts()){
                $relations->the_post();
    
                //Check if selected
                $checked = '';
                foreach ($values as $value) {
                    if ($value  == '"'.get_the_ID().'"') {
                        $checked = 'checked';
                    }
                }
    
                //Prints field
                $this->printed_field .= '<input type="checkbox" id="'.$this->key.'_'.get_the_ID().'" name="'.$this->key.'[]" value="'.get_the_ID().'" '.$checked.'>';
                //Print label
                $this->printed_field .= '<label class="name_label_editor" for="'.$this->key.'_'.get_the_ID().'">'.get_the_title().'</label></br>';

                // add search function
            }
        }

        //Hidden field to make sure you can un select all the boxes
        $this->printed_field .= '<input style="display:none;" type="checkbox" id="'.$this->key.'_none" name="'.$this->key.'[]" value="none" checked>';
        
        
    }
    function type_map() {
        $value = sanitize_text_field(get_post_meta($this->postID, $this->key, TRUE));
        //$value_array = explode(':;:', $value);
        
        $this->printed_field = '<div id="map_container"></div>';
        $this->printed_field .= '<div id="map_information"></div>';
        $this->printed_field .= '<input type="text" id="leaflet-post-value" style="display:none;" name="'.$this->key.'" value="'.$value.'">';

        /*$this->printed_field .= '<label id="'.$this->key.'-adress-label" for="'.$this->key.'-adress">Adress:</label><br>';
        $this->printed_field .= '<input type="text" id="leaflet-adress" name="'.$this->key.'-adress" value="'.$value_array[1].'">';
        $this->printed_field .= '<ul id="leaflet-search-results"></ul>';

        if ($value_array[4] == 1) {
            $checked = 'checked';
        }else{
            $checked = '';
        }
        $this->printed_field .= '<input type="checkbox" id="leaflet-custom-adress" name="'.$this->key.'-custom-adress" '.$checked.'>';
        $this->printed_field .= '<label for="'.$this->key.'-custom-adress">fyll i adress separat från markören på kartan</label></br>';

        if ($value_array[5] == 1) {
            $displayed = '';
        }else{
            $displayed = 'style="display:none;"';
        }

        $this->printed_field .= '<label '.$displayed.' id="leaflet-descriptive-name-label" for="'.$this->key.'-descriptive-name">Beskrivande namn:</label></br>';
        $this->printed_field .= '<input '.$displayed.' type="text" id="leaflet-descriptive-name" name="'.$this->key.'-descriptive-name" value="'.$value_array[0].'"></br>';

        if ($value_array[5] == 1) {
            $checked = 'checked';
        }else{
            $checked = '';
        }
        $this->printed_field .= '<input type="checkbox" id="leaflet-custom-descriptive-name" name="'.$this->key.'-custom-descriptive-name" '.$checked.'>';
        $this->printed_field .= '<label for="'.$this->key.'-custom-descriptive-name">fyll i namn på platsen (eg. fenix, hembygdsgården mm)</label>';

        $this->printed_field .= '</br>';
        
        $this->printed_field .= '<div id="leaflet-latlng"><ul><li data-lat="'.$value_array[2].'">Lat: '.$value_array[2].'</li><li data-lng="'.$value_array[3].'">Long: '.$value_array[3].'</li></ul></div>';*/
        
        
        //$this->printed_field .= '</div>';

    }
}
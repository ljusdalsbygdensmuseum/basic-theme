<?php

//metaboxes class
class ljm_custom_column 
{
    public string $post_type;
    public array $columns;
    public function __construct($post_type, $column_args){
        //Extracting and setting the arguments
        $this->post_type = $post_type;

        $this->columns = $column_args;
        
        //Printing the columns
        $this->print_column();
    }
    public function print_column() {
        //Making the column itself
        add_filter('manage_'.$this->post_type.'_posts_columns', array($this, 'post_columns'));

        //Populating custom columns
        add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'custom_column_content'), 10, 2);

        //Making columns sortable
        add_filter('manage_edit-'.$this->post_type.'_sortable_columns', array($this, 'sortable_columns'), 10, 1);

        //Custom orderby
        add_action('pre_get_posts', array($this, 'custom_orderby'), 10, 1);
    }
    public function post_columns() {
        $new_columns = array();

        //Adding the new columns
        foreach ($this->columns as $column) {
            var_dump($column);
            $new_columns[$column['ID']] = $column['name'];
        }

        return $new_columns;
    }
    public function custom_column_content($column_id, $post_id) {
        //Printing the content
        foreach ($this->columns as $column) {
            //Checking if it is the correct column
            if ($column_id != $column['ID']) {
                continue;
            }
            
            //Getting and formating content
            if (isset($column['values']) && !empty($column['values'])) {
                if (isset($column['format']) && !empty($column['format'])) {
                    $formated_value = $column['format'];

                    //Looping throug each value and replacing it in formated value
                    foreach ($column['values'] as $value_key) {
                        //Key for selecting the value
                        $key = $this->post_type.'_'.$value_key['value'];

                        $value = get_post_meta($post_id, $key, TRUE);

                        //Formating if date
                        if ($value_key['type'] == 'date') {
                            $value = $this->case_date($value);
                        }
                        //Formating if post relation
                        if ($value_key['type'] == 'post_relation') {
                            $value = $this->case_post_relation($value);
                        }

                        //Adding the values to their place
                        $formated_value = str_replace('%'.$value_key['value'].'%', $value, $formated_value);  
                    
                    }

                    //Printing the content
                    echo $formated_value;
                    
                }else{
                    //Key for selecting the value
                    $key = $this->post_type.'_'.$column['values'][0]['value'];

                    //Printing value
                    $value = get_post_meta($post_id, $key, TRUE);

                    //Formating if date
                    if ($column['values'][0]['type'] == 'date') {
                        $value = $this->case_date($value);
                    }
                    //Formating if date
                    if ($column['values'][0]['type'] == 'post_relation') {
                        $value = $this->case_post_relation($value);
                    }

                    //Printing the content
                    echo $value;
                }
            }
        }
    }
    public function sortable_columns($sortable_columns) {
        //Pushing the columns that should be sortable
        foreach ($this->columns as $column) {
            if (isset($column['sortable']) && $column['sortable']) {
                $sortable_columns[$column['ID']] = $column['ID'];
                
            }
        }
        return $sortable_columns;
    }
    public function custom_orderby($query) {
        //Checking if user is admin
        if (!is_admin()) {
            return;
        }

        foreach ($this->columns as $column) {
            if (isset($column['sortable']) && $column['sortable']) {
                //Makes key
                if (isset($column['orderby']) && !empty($column['orderby'])) {
                    $key = $this->post_type.'_'.$column['orderby'];

                }else{
                    $key = $this->post_type.'_'.$column['values'][0]['value'];

                }

                //Setting query
                if ($query->is_main_query() && ( $orderby = $query->get( 'orderby' ) && is_admin()) && $query->get('post_type') == $this->post_type) {
                    //Checking if correct column
                    if ($query->get( 'orderby' ) == $column['ID']) {
                        //Setting meta key
                        $query->set('meta_key', $key);

                        //Checking if orderby integer or not and setting orderby
                        if (isset($column['orderby_type']) && empty($column['orderby_type']) && $column['orderby_type'] == 'int') {
                            $query->set('orderby','meta_value_num');

                        }else {
                            $query->set('orderby','meta_value');

                        }
                    }                  
                }
            }
        }
    }
    public function case_date($value) {
        if ($value) {
            $value = substr_replace($value, '-', 4, 0 );
            $value = substr_replace($value, '-', 7, 0 );
            return $value;
        }
    }
    public function case_post_relation($value) {
        if ($value) {
            $value_array = '<ul>';

            //Iterating on each value
            foreach (explode(', ', str_replace('"', '', $value)) as $id) {
                $value_array .= '<li><a href="'.get_permalink($id).'">'.get_the_title($id).'</a></li>';
            }
            $value_array .= '</ul>';

            return $value_array;
        }
    }
}
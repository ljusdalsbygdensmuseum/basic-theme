<?php
// metaboxes class
class custom_metavalue
{
    public string $name;
    public string $key;
    public string $label;
    public string $placeholder;
    public string $description;
    public string $type;
    public string $restAPI;

    public function __construct(
        string $name, 
        string $key, 
        string $label = '', 
        string $placeholder = '', 
        string $description = '', 
        string $type = 'text', //suports type: text, textarea, number, date, default = text
        bool $restAPI = TRUE
        )
    {
        $this->name = $name;
        $this->key = $key;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->description = $description;
        $this->type = $type;
        $this->restAPI = $restAPI;
    }
}

<?php
    // hcard profile for hkit
    
    $this->root_class = 'hproduct';
    
    $this->classes = array( 
        'name', 
        'brand',
        'uri',
        'image', 'thumb',
        'description',
    );
    
    // classes that must only appear once per card
    $this->singles = array(
        'name'
    );
    
    // classes that are required (not strictly enforced - give at least one!)
    $this->required = array(
        'name'
    );

    $this->att_map = array(
        'name'    => array('IMG|alt'),
        'uri'     => array('A|href', 'IMG|src', 'AREA|href'),
        'image'   => array('IMG|src'),
    );

    
    $this->callbacks = array(
        'uri'    => array($this, 'resolvePath'),
    );

    function hKit_hproduct_post($a)
    {
        return $a;
    }
?>
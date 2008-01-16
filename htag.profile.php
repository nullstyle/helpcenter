<?php
    // hcard profile for hkit
    
    $this->root_class = 'tag';
    
    $this->classes = array( 
        'name', 
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
    );

    
    $this->callbacks = array(
    );

    function hKit_htag_post($a)
    {
        return $a;
    }
?>

<?php
require_once('Sprinkles.php');

print "PHP major version: " . php_major_version() . "<br />";

print($needs_unbollocks ? "PHP version thought to be pre-6. Needs unbollocks" 
                        : "PHP version thought to be 6 or later. No need for unbollocks." );
?>
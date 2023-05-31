<?php
// page2.php:
  
  // this is needed for the unserialize to work properly.
//   include("classa.inc");

$s = file_get_contents('store');
$b = unserialize($s);

// now use the function show_one() of the $a object.  
$a->show_one();
?>
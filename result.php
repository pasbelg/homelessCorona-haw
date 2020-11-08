<?php 
require('conf.php');
require('functions/formFunctions.php');
$input = postToArray($_POST, csvToArray($fileC));
print("<pre>".print_r($input,true)."</pre>");
?>
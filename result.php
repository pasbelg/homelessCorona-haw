<?php 
require('conf.php');
require('functions/formFunctions.php');
$input = postToArray($_POST, csvToArray($fileC));
saveEntries($input, $userData);
#echo checkUserExistence($input['metaInfo']['meta-name'], $userData) ? 'true' : 'false';
#saveEntrys($input, $outputPath.$input[])
#print("<pre>".print_r($input,true)."</pre>");
#echo json_encode($input);
$json = readEntries('Pascal');
print("<pre>".print_r($json,true)."</pre>");
?>
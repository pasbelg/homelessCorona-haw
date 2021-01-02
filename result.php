<?php 
require('conf.php');
require('functions/formFunctions.php');

// Script start
$rustart = getrusage();

// Code ...



#Beim aufrufen der Seite wird überprüft ob ein Token mitgegeben wurde.
#Wenn ja wird überprüft ob es für den Token schon eine Datei gibt.
#Gibt es eine wird direkt das Ergebnis ausgegeben
#Wurde keine Token mitgegeben wird eine neue Datei generiert.
#Probleme:
    #1. Es kann relativ leicht mit F5 eine neue Datei angelegt werden (es solle überprüft werden ob die gleichen Daten gesendet werden)
if(isset($_GET['token'])){
    $result = readEntries($_GET['token']);
    if($result != false){
        echo 'Hier ist dein Ergebnis:<br>';
        print("<pre>".print_r($result,true)."</pre>");
    } else {
        echo 'Deine Daten wurden leider nicht gefunden. <a href="index.php">Hier</a> kannst du die Kosten auf der Straße erneut berechnen';
    }
} else {
    if (!empty($_POST['meta-name'])) {
        $input = postToArray($_POST, csvToArray($fileC));
        saveEntries($input, $input['metaInfo']['meta-token']);
        echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?token=' . $input['metaInfo']['meta-token'];
    } else {
        echo 'Es wurden keine Daten übermittelt. Bitte fülle das <a href="index.php">Formular<a> aus';
    }

}
#echo checkUserExistence($input['metaInfo']['meta-name'], $userData) ? 'true' : 'false';
#saveEntrys($input, $outputPath.$input[])
#print("<pre>".print_r($input,true)."</pre>");
#echo json_encode($input);
#$json = readEntries('Pascal');
#print("<pre>".print_r($json,true)."</pre>");
// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";
?>
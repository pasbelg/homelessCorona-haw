<?php 
include('header.php');
/*
require('conf.php');
require('functions/formFunctions.php');
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Was kostet die Straße?</title>
  </head>
  <body>*/


#Beim aufrufen der Seite wird überprüft ob ein Token mitgegeben wurde.
#Wenn ja wird überprüft ob es für den Token schon eine Datei gibt.
#Gibt es eine wird direkt das Ergebnis ausgegeben
#Wurde keine Token mitgegeben wird eine neue Datei generiert.
if(isset($_GET['token'])){
    $result = readEntries($_GET['token']);
    if($result != false){
        $link = $_SERVER['REQUEST_URI'];
        echo 'Hier ist dein Ergebnis:<br>';
        #print("<pre>".print_r($result,true)."</pre>");
        $monthlyExpenses = calcExpenses($result);
        echo 'Deine monatlichen Kosten sind: ' . $monthlyExpenses . ' €<br>';
        echo 'Jährlich sind das: ' . $monthlyExpenses * 12 . ' €<br>';
        echo 'Dein Ergebnis kann für 30 Tage mit diesem Link aufgerufen werden: <a href="' . $link .'">'.  $_SERVER['HTTP_HOST'] . $link .' </a>';
    } else {
        echo 'Deine Daten wurden leider nicht gefunden. <a href="index.php">Hier</a> kannst du die Kosten auf der Straße erneut berechnen';
    }
} else {
    if (!empty($_POST['meta-name'])) {
        print_r($_POST);
        $input = postToArray($_POST, csvToArray($fileC));
        saveEntries($input, $input['metaInfo']['meta-token']);
        header('Location: ?token=' . $input['metaInfo']['meta-token']);
        die();
    } else {
        echo 'Die Daten konnten aufgrund eines Fehlers nicht gespeichert werden. Hast du deinen Namen angegeben? Bitte fülle das <a href="index.php">Formular<a> nochmal aus';
    }

}
?>
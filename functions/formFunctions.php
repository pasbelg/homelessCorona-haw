<?php
$fileC = 'files/categorys.csv';
$fileQ = 'files/questions.csv';

#Funktion zum einlesen der CSV Dateien und umwandeln in ein Array
function csvToArray($filepath){
    $csvParse = array_map('str_getcsv', file($filepath));
    array_shift($csvParse);
    foreach($csvParse as $row){
        foreach($row as $data){
            $csvData[] = explode(';', $data);
        }
    }
    return $csvData;
}

#Funktion zum generieren des Formulars anhand der erstellen Arrays aus den CSVs
function genForm($categorys, $questions){
    foreach($categorys as $category){
        echo '<b>'.$category[1].'</b>';
        echo '<br>';
        foreach($questions as $question){
            if($category[0] == $question[0]){
                echo '<input type="checkbox" id="'.$question[1].'" name="scales" value="'.$question[2].'">
                        <label for="'.$question[1].'">'.$question[1].'</label>';
                echo '<br>';
            }
        }
        echo '<br><br><br>';
    }
}
genForm(csvToArray($fileC),csvToArray($fileQ));
?>
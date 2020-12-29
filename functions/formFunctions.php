<?php
$fileC = $inputPath . 'categories.csv';
$fileQ = $inputPath . 'questions.csv';
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
function genForm($categories, $questions){
    foreach($categories as $category){
        $qCount = 1;
        echo '<b>'.$category[1].'</b>';
        echo '<br>';
        foreach($questions as $question){
            if($category[0] == $question[0]){
                echo '<input type="checkbox" id="'.$question[1].'" name='.$category[1].$qCount.'" value="'.$question[2].'">
                        <label for="'.$question[1].'">'.$question[1].'</label>';
                echo '<br>';
                $qCount++;
            }
        }
        echo '<br><br><br>';
    }
}

/*Funktion zum sortieren der übertragenen Formulardaten
Zielstruktur:
Array
(
    [metaInfo] => Array
        (
            [meta-Info1] => Eingetragene Metainfo1
            [meta-Info2] => Eingetragene Metainfo2
        )
    [costInfo] => Array
        (
            [Kategorie1] => Array
                (
                    [0] => Angekreuztes Feld in Kategorie1
                )

            [Kategorie2] => Array
                (
                    [0] => Angekreuztes Feld in Kategorie2
                    [1] => Angekreuztes Feld in Kategorie2
                )
        )
)
*/
function postToArray($formData, $categories){
    $formArray = array(
        'metaInfo'  => array(),
        'costInfo' => array()
    );
    foreach($categories as $category){
        #array_push($formArray['costInfo'], $category[1]); #Für späteres debugging noch behalten wegen Dopplung ([3] => Hygiene [Hygiene] => Array) evtl unnötig
        $formArray['costInfo'][$category[1]] = array();
        foreach($formData as $key => $value){ #Für jeden Eintrag im $_POST-Array
            if(strpos($key, $category[1]) !== false){ #strpos ist komisch https://stackoverflow.com/questions/35854071/strpos-not-working-for-a-certain-string?rq=1
                array_push($formArray['costInfo'][$category[1]], $value);
                unset($formData[$key]);
            } else if (strpos($key, 'meta') !== false) { #Alles bei dem im Schlüssel "meta" steht
                $formArray['metaInfo'][$key] = $value;
            }
        }
    }
    return $formArray;
}

#Funktion um zu überprüfen ob es schon Daten zu einem Usernamen gibt.
function checkUserExistence($username){
    global $userData;
    $files = array_diff(scandir($userData), array('.', '..'));
    #Für jede Datei in /files/out/userData/
    foreach($files as $file) {
        #Prüfen ob der Username in der Datei gefunden wurde
        if(is_numeric(stripos($file, $username.'_'))){
            return $file;
        } else {
            return false;
        }
    }
}
#Funktion um die eingetragenen Formulardaten in eine Datei zu schreiben (Optimierungsbedarf: User können andere überchreiben)
function saveEntries($formData, $destination){
    #Extrahieren des Usernamen aus dem Array
    $username = $formData['metaInfo']['meta-name'];
    #Gibt es den angegebenen User schon wird die bisherige Datei gelöscht (überschrieben)
    $userFile = checkUserExistence($username, $destination);
    #Dateipfad und -namen setzen (/files/out/userData/username_UnixTimestamp.json)
    $outputFile = $destination.$username.'_'.time().'.json';
    $content = json_encode($formData);
    #Wenn User schon eine Datei hat soll diese gelöscht werden (Aktualisierung mit neuer Datei).
    if($userFile != false){
        unlink($destination.$userFile);
    }
    #Schreiben der Datei mit angegebenen Daten
    $f = fopen($outputFile, 'w') or die('Unable to open file!');
    fwrite($f, $content);
}

#Funktion um die Einträge anhand eines Usernamen zu lesen
function readEntries($username){
    global $userData;
    $userFile = checkUserExistence($username);
    #Wenn User schon eine Datei hat wird diese ausgelesen und zurückgegeben
    if($userFile != false){
        $content = file_get_contents($userData.$userFile);
        return json_decode($content);
    #Hat er keine Datei wird false zurückgegeben
    } else {
        return false;
    }
}
?>
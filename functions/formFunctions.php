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
        $formArray['metaInfo']['meta-token'] = hash('md5', $formData['meta-name'].time());
    }
    return $formArray;
}

#Funktion um zu überprüfen ob es schon Daten zu einem Usernamen gibt.
function checkUserExistence($token){
    global $userData;
    $files = array_diff(scandir($userData), array('.', '..'));
    #Für jede Datei in /files/out/userData/
    foreach($files as $file) {
        if(strlen($token) == 32){
            #Prüfen ob schon eine Datei mit diesem Token exisitert.
            if(is_numeric(stripos($file, '_'.$token))){
                return $file;
            }
        }
    }
    return false;
}
#Funktion um die eingetragenen Formulardaten in eine Datei zu schreiben (Optimierungsbedarf: User können andere überchreiben)
function saveEntries($formData, $token){
    global $userData;
    #Extrahieren des Usernamen aus dem Array
    $token = $formData['metaInfo']['meta-token'];
    #Gibt es den angegebenen User schon wird die bisherige Datei gelöscht (überschrieben)
    $userFile = checkUserExistence($token, $userData);
    #Dateipfad und -namen setzen (/files/out/userData/UnixTimestamp_MD5Hash(username, UnixTimestamp).json)
    $outputFile = $userData.date('Y-m-d-H-i-s').'_'.$token.'.json';
    $content = json_encode($formData);
    #Wenn User schon eine Datei hat soll diese gelöscht werden (Aktualisierung mit neuer Datei).
    if($userFile != false){
        unlink($userData.$userFile);
    }
    #Schreiben der Datei mit angegebenen Daten
    $f = fopen($outputFile, 'w') or die('Unable to open file!');
    fwrite($f, $content);
}

#Funktion um die Einträge anhand eines Usernamen zu lesen
function readEntries($token){
    global $userData;
    $userFile = checkUserExistence($token);
    #Wenn User schon eine Datei hat wird diese ausgelesen und zurückgegeben
    if($userFile != false){
        $content = file_get_contents($userData.$userFile);
        return objectToArray(json_decode($content));
    #Hat er keine Datei wird false zurückgegeben
    } else {
        return false;
    }
}

#Wandelt die von json_decode zurückgegebenen Objekte in ein Array um
function objectToArray($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
        $new = array();
        foreach($obj as $key => $val) {
            $new[$key] = objectToArray($val);
        }
    }
    else $new = $obj;
    return $new;       
}

#Addiert für jedes angekreuzte Feld die Kosten
function calcExpenses($formArray){
    $monthlyExpenses = 0;
    #$formArray = get_object_vars($formArray);
    foreach($formArray['costInfo'] as $category){
        foreach($category as $expense){
            $monthlyExpenses += $expense;
        }
    }
    return $monthlyExpenses;
}
?>
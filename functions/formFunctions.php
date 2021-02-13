<?php
$fileC = $inputPath . 'categories.csv';
$fileQ = $inputPath . 'questions.csv';
#Funktion zum einlesen der CSV Dateien und umwandeln in ein Array
function csvToArray($filepath){
    $csvParse = file($filepath);
    $header = array_shift($csvParse);
    $header = rtrim($header);
    $header = explode(';', $header);
    foreach($csvParse as $rowData){
        $rowData = rtrim($rowData);
        $csvData[] = explode(';', $rowData);
    }
    $rowCount = 0;
    foreach($csvData as $row){
        foreach($header as $key => $description){
            $csvData[$rowCount][$description] = $csvData[$rowCount][$key];
            unset($csvData[$rowCount][$key]);
        }
        $rowCount++;
    }
    return $csvData;
}

#Funktion zum generieren des Formulars anhand der erstellen Arrays aus den CSVs
# Ich würde das ganze gerne in Arrays abspeichern und wiedergeben, um das Formular und alles flexibler gestalten zu können
# Vielleicht den Infotext gar nicht hier verarbeiten sondern dann in einer neuen Loop.
function genChoices($choices, $question){
    foreach($choices as $choice){
        $choiceCount = $choice['position'];
        if($choice['questionID'] == $question){
            #echo '<input type="checkbox" class="check" id="'.$choice['text'].'" name="q'.$question.'choice'.$choiceCount.'" value="'.$choice['cost'].'"'.($choice['greyed'] == 'ja' ? 'disabled="disabled"' : '').'">
            #        <label for="'.$choice['text'].'">'.$choice['text'].'</label><br>';
            echo '<input type="radio" class="check" id="'.$choice['text'].'" name="q'.$question.'" value="'.$choice['cost'].'"'.($choice['greyed'] == 'ja' ? 'disabled="disabled"' : '').'">
                    <label '.($choice['greyed'] == 'ja' ? 'style="text-decoration: line-through;"' : '').' for="'.$choice['text'].'">'.$choice['text'].'</label><br>';
        }
    }
}

function spamCheck($ip, $savedDataLocation){
    $newToken = hash('md5', $ip.time());
    $files = scandir($savedDataLocation);
    $files = array_diff($files, array('.', '..'));
    foreach($files as $file){
        $fileToken = str_replace('.json', '', explode('_', $file)[1]);
        for ($i = -5; $i <= 5; $i++) {
            $time = time()+$i;
            $tokenCheck = hash('md5', $ip.$time);
            if($fileToken == $tokenCheck){
                return false;
            }
        }
    }
    return $newToken;
}

function genPosition($expenses, $question){
    $positionExpense = 0;
    foreach($expenses as $expense){
        $positionExpense += $expense;
    }
    if($positionExpense > 0){
        $position = '<li class="line"><span class="item">'.$question['category'].'</span><span class="price">'.$positionExpense.' €</span><li>';
    } else {
        $position = '';
    }
    return $position;
}

function genResultText($selection, $allQuestions, $allChoices){
    $resultText = '';
    foreach($selection as $questionID => $question){
        if(!empty($question)){
            foreach($question as $choiceID => $selectedChoices){
                foreach($allChoices as $availableChoice){
                    if($questionID == $availableChoice['questionID']/* Nur für Mehrfachauswahl AND $choiceID == $availableChoice['position']*/){
                        $resultText .= $availableChoice['description'].' ';
                    }
                }
                
            }
        } else {
            foreach($allQuestions as $question){
                if($questionID == $question['questionID']){
                    $resultText .= $question['choicesMissingInfo'].' ';
                }
            }
        }
    }
    return $resultText;
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
function postToArray($formData, $questions, $token){
    $formArray = array(
        'metaInfo'  => array(),
        'costInfo' => array()
    );
    foreach($questions as $question){
        #array_push($formArray['costInfo'], $category[1]); #Für späteres debugging noch behalten wegen Dopplung ([3] => Hygiene [Hygiene] => Array) evtl unnötig
        $formArray['costInfo'][$question['questionID']] = array();
        foreach($formData as $key => $value){ #Für jeden Eintrag im $_POST-Array
            if($key == 'q'.$question['questionID']){
                echo $key.' '.$value.' '. 'q'.$question['questionID'] .'<br>';
                $formArray['costInfo'][$question['questionID']][$question['questionID']] = $value;
                unset($formData[$key]);
            /* Da zuerst eine Mehrfachauswahl mit Checkboxen geplant war ist hierfür der Code:
            if(strpos($key, 'q'.$question['questionID'].'choice') !== false){
                $choiceID = explode('choice', $key)[1];
                $formArray['costInfo'][$question['questionID']][$choiceID] = $value;
                unset($formData[$key]);
            */
            } else if (strpos($key, 'meta') !== false) { #Alles bei dem im Schlüssel "meta" steht
                $formArray['metaInfo'][$key] = $value;
            }
        }
        #$formArray['metaInfo']['meta-token'] = hash('md5', $formData['meta-ip'].time());
        $formArray['metaInfo']['meta-token'] = $token;
    }
    print_r($formArray);
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
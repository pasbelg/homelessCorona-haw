<?php
$fileC = $inputFiles . 'categories.csv';
$fileQ = $inputFiles . 'questions.csv';
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
    print_r($categories);
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
?>
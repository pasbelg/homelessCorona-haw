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
?>
<div class="wrapper">
    <form action="result.php" method="post">
        <!--Bitte alle Metainformationen mit dem Namen meta- Kennzeichnen. Das ist für die Zuordung der Meta Infos nötig. -->
        <div class="formSection" id="section0">
          <div class="question sectionRight">
            <b><label for="meta-name">Bitte gib einen Pseudonym oder einen Namen ein</label></b><br>
            <input type="text" id="meta-name" name="meta-name">
          </div>
          <a href="#section1"><button type="button">Nächste Frage</button></a>
        </div>
        <!--Die anzukreuzenden Felder werden über die CSV-Dateien in files/in/ generiert -->
        <?php
        foreach(csvToArray($fileC) as $question){
          echo '<div class="formSection" id="section'.$question['questionID'].'">
                  <div class="question">
                    <div class="questionCol sectionLeft">'.$question['description'].'</div>
                      <div class="questionCol sectionRight">';
                        echo '<b>'.$question['text'].'</b><br>';
                        genChoices(csvToArray($fileQ), $question['questionID']);
          echo '      </div>
                    </div>
                    <a href="#section'.($question['questionID']-1).'"><button type="button">Vorige Frage</button></a>
                    <a href="#section'.($question['questionID']+1).'"><button type="button">Nächste Frage</button></a>
                  </div>';
        }
        ?>
      <div class="formSection" id="section<?php echo count(csvToArray($fileC))+1?>">
          <div class="question">
          <b><label for="submit">Vielen Dank für deine Angaben. Wenn du jetzt auf Senden</label></b><br>
            <input type="submit" id="submit"  value="Mein Ergebnis"></input>
          </div>
      </div>
    </form>
      </div>
  </body>
</html>

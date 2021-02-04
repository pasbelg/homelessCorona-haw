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
        <div class="question">
        <div class="questionCol sectionLeft">
        <img src="https://hungry-varahamihira-72424b.netlify.app/img/becher.jpg" alt="logo" />
        </div>
          <div class="question sectionRight">
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
          <a href="#section1"><button type="button">Jetzt Starten</button></a>
          </div>
          </div>
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
          echo '     <br><a href="#section'.($question['questionID']-1).'"><button type="button">Vorige Frage</button></a>
          <a href="#section'.($question['questionID']+1).'"><button type="button">Nächste Frage</button></a>
                      </div>
                    </div>
                    
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

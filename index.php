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
    <form id="form" action="result.php" method="post">
        <!--Bitte alle Metainformationen mit dem Namen meta- Kennzeichnen. Das ist für die Zuordung der Meta Infos nötig. -->
        <div class="formSection" id="section0">
        <div class="question">
        <div class="questionCol sectionLeft">
        <img src="https://hungry-varahamihira-72424b.netlify.app/img/becher.jpg" alt="logo" />
        </div>
        <!--Code von Basti-->  
        <!--
          <div class="left-box">
            <img src="/img/frau.jpg" alt="frau" />
            <h2>Frauen</h2>
            <br />
            <p>
                Verfasst von: <br />
                Name1, Name2, Name3
            </p>
          </div>
          <div class="right-box">
            <h2>Subtitle</h2>
            <hr />
            <p>bla</p>
          </div>
-->
 <!--Code von Pascal-->        
        
          <div class="question sectionRight">
          <h2>Kosten</h2>
          <br>
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
          <br><br><br><br>
          <a href="#section1">Jetzt Starten</a>
          </div>
          </div>
        </div>
        <!--Die anzukreuzenden Felder werden über die CSV-Dateien in files/in/ generiert -->
        <?php
        $sectionCounter = 1;
        foreach(csvToArray($fileC) as $question){
          echo '<div class="formSection" id="section'.$sectionCounter.'">
                  <div class="question">
                    <div class="questionCol sectionLeft">'.$question['description'].'</div>
                    <div class="questionCol sectionRight">';
                        echo '<b>'.$question['text'].'</b><br>';
                        genChoices(csvToArray($fileQ), $question['questionID']);
          echo '          <br><br><br><br>
                          <a href="#section'.($sectionCounter-1).'"><svg xmlns="http://www.w3.org/2000/svg" width="24" color="255 255 255" height="24" viewBox="0 0 24 24"><path d="M0 16.67l2.829 2.83 9.175-9.339 9.167 9.339 2.829-2.83-11.996-12.17z"/></svg></a>
                          <a href="#section'.($sectionCounter+1).'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg></a>
                      </div>
                    </div>
                  </div>';
                  $sectionCounter++;
        }
        ?>
      <div class="formSection" id="section<?php echo count(csvToArray($fileC))+1?>">
          <div class="question">
            <div id="formSubmit">
            <a class="effect1" href="javascript:{}" onclick="document.getElementById('form').submit(); return false;">Mein Ergebnis
            <!-- Alter Button <input id="submitButton" type="submit" id="submit"  value="Mein Ergebnis">-->
          <span class="bg"></span>
          </a>
          </input>
      </div>
          </div>
      </div>
    </form>
      </div>
  </body>
</html>

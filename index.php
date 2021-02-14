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

        
        <!--Code von Basti-->  
        <div class="formSection" id="section0">
          <div class="left-box">
            <img src="media/becher.jpg" alt="becher" />
            <h2>Kosten</h2>
            <br />
            <p>
                Verfasst von: <br />
                Emma, Lisa und Pascal
            </p>
          </div>
          <div class="right-box">
            <h2>Kosten</h2>
            <hr />
            <p>
              Wie viel Geld brauche ich heute, wenn ich drei ganze Mahlzeiten am Tag essen möchte? Lohnt es sich wirklich, meine letzten zwei Euro für eine warme Dusche auszugeben oder kaufe ich mir doch lieber eine neue Hose? Und was mache ich, wenn ich beim Schlafplatz meinen Hund nicht reinbringen darf? 
              Mit diesen Fragen werden viele Menschen täglich konfrontiert. Wie vieles im Leben drehen sich auch bei Menschen ohne einen festen Wohnsitz die Fragen ums Geld. Doch während es häufig bei Personen um banale Entscheidungen geht, handelt es sich bei Obdachlosen manchmal schon um die Entscheidung zwischen zwei grundlegenden Bedürfnissen. 
              Ein besonderer Fokus liegt hierbei auf die andauernde Pandemie durch das Coronavirus SARS-CoV-2. Durch die Pandemie ist das Leben auf der Straße noch schwieriger geworden.
              Der Fragebogen zeigt eine Übersicht über die Kosten eines Straßenlebens. Dazu gibt es mehrere Fragen aus dem alltäglichen Leben. Je nach Auswahl werden nach Beantwortung der Fragen die monatlichen Kosten kalkuliert und man kann sich einen Überblick über seine Entscheidungen verschaffen. 
              Dazu wählst du einfach immer <strong>eine Antwort pro Frage</strong> aus.
            </p>
            <a class="formNav" href="#section1">Jetzt Starten</a>    
          </div>
          
</div>

 <!--Code von Pascal-->        
<!--
 <div class="formSection" id="section0">
        <div class="question">
        <div class="questionCol sectionLeft">
          <img src="https://hungry-varahamihira-72424b.netlify.app/img/becher.jpg" alt="logo" />
        </div>
          <div class="question sectionRight">
            <h2>Kosten</h2>
            <br>
            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            <br><br><br><br>
            <a href="#section1">Jetzt Starten</a>
            </div>
          </div>
        </div>
-->
        <!--Die anzukreuzenden Felder werden über die CSV-Dateien in files/in/ generiert -->
        <?php
        $sectionCounter = 1;
        foreach(csvToArray($fileC) as $question){
          echo '<div class="formSection" id="section'.$sectionCounter.'">
                  <div class="question">
                    <div class="questionCol sectionLeft">'.$question['description'].'</div>
                    <div class="questionCol sectionRight">';
                        echo '<b>'.$question['text'].'</b><br>
                              <fieldset style="border:none" id="q'.$question['questionID'].'">';
                        genChoices(csvToArray($fileQ), $question['questionID']);
          echo '           </fieldset><br><br><br><br>
                          <a class="formNav" href="#section'.($sectionCounter-1).'"><svg xmlns="http://www.w3.org/2000/svg" width="24" color="255 255 255" height="24" viewBox="0 0 24 24"><path d="M0 16.67l2.829 2.83 9.175-9.339 9.167 9.339 2.829-2.83-11.996-12.17z"/></svg></a>
                          <a class="formNav" href="#section'.($sectionCounter+1).'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg></a>
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

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
    <form action="result.php" method="post">
        <!--Bitte alle Metainformationen mit dem Namen meta- Kennzeichnen. Das ist für die Zuordung der Meta Infos nötig. -->
        <label for="meta-name">Damit du die Daten wieder aufrufen kannst brauen gib ein Pseudonym oder einen Namen ab</label>
        <input type="text" id="meta-name" name="meta-name">
        <!--Die anzukreuzenden Felder werden über die CSV-Dateien in files/in/ generiert -->
        <?php
        foreach(csvToArray($fileC) as $question){
          echo $question[1];
          echo '<br>';
          genChoices(csvToArray($fileQ), $question[0]);
          echo '<br><br>';
        }
        ?>
      <p><input type="submit" /></p>
    </form>
  </body>
</html>

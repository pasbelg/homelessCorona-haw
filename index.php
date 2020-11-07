<?php
require('conf.php');
require('functions/formFunctions.php');
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Was kostet die Straße?</title>
  </head>
  <body>
    <form action="result.php" method="post">
        <!--Bitte alle Metainformationen mit dem Namen meta- Kennzeichnen. Das ist für die Zuordung der Meta Infos nötig. -->
        <label for="meta-name">Pseudonym oder Name</label>
        <input type="text" id="meta-name" name="meta-name"><br>
        <label for="meta-streetYears">Jahre auf der Straße</label>
        <input type="number" id="meta-streetYears" name="meta-streetYears" min="1" max="100"><br>
        <!--Die anzukreuzenden Felder werden über die CSV-Dateien in files/in/ generiert -->
        <?php genForm(csvToArray($fileC),csvToArray($fileQ));?>
      <p><input type="submit" /></p>
    </form>
  </body>
</html>

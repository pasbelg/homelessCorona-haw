<?php
require("functions/formFunctions.php");

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
        <?php genForm(csvToArray($fileC),csvToArray($fileQ));?>
      <p><input type="submit" /></p>
    </form>
  </body>
</html>

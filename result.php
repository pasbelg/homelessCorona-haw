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
#Beim aufrufen der Seite wird überprüft ob ein Token mitgegeben wurde.
#Wenn ja wird überprüft ob es für den Token schon eine Datei gibt.
#Gibt es eine wird direkt das Ergebnis ausgegeben
#Wurde keine Token mitgegeben wird eine neue Datei generiert.
$ip = $_SERVER['REMOTE_ADDR'];
$token = spamCheck($ip, $userData);
if(isset($_GET['token'])){
    $result = readEntries($_GET['token']);
    if($result != false){
        $link = '<a href="' . $_SERVER['REQUEST_URI'] .'">'.  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] .' </a>';
        $positions = $result['costInfo'];
        #print("<pre>".print_r($result,true)."</pre>");
        $monthlyExpenses = calcExpenses($result);
        #<a href="' . $link .'">'.  $_SERVER['HTTP_HOST'] . $link .' </a>
    } else {
        header('Location: error.php');
    }
} else {
    if ($token) {
        $_POST['meta-ip'] = $ip;
        $input = postToArray($_POST, csvToArray($fileC), $token);
        saveEntries($input, $token);
        header('Location: ?token=' . $input['metaInfo']['meta-token']);
        die();
    } else {
        header('Location: error.php');
    }

}
?>
<div class="wrapper">
<div id="resultReceipt">
            <h2 class="title">Vielen Dank.</h2>
            <p class="subtitle">Hier sind deine monatlichen Ausgaben:</p>
            <ul class="lines">
                <?php
                foreach(csvToArray($fileC) as $question){
                    $position = genPosition($positions[$question['questionID']], $question);
                    echo $position;
                }
                ?>
            </ul>
      <p class="total">
        <span class="total__item">Gesamt</span>
        <span class="total__price"><?php echo $monthlyExpenses;?> €</span>
      </p>
      <br>
</div>
    
    <div id="generatedResultText">
        <h2 >Dein Leben auf der Straße</h2>    
        <?php echo genResultText($positions, csvToArray($fileC), csvToArray($fileQ))?>
        <br><br><br>
        <div>Du kannst dieses Ergebnis unter folgendem Link abrufen:<br> <?php echo $link;?></div>    
    </div>
        
    </div>            
</section>

</body>
            </html>
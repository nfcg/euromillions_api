<?php

   $available_formats = array(
       'txt',
       'xml',
       'json'
   );
   $available_formats = array_fill_keys($available_formats, 1);
  
   $f = $_REQUEST["format"];
   if (!$available_formats[$f]) {
       $f = "txt";
   }
  
   $q = trim($_REQUEST['result']);
   if (!preg_match('/^[a-z0-9 .\-]+$/i', $q)) {
       $q = "";
   }
  
   try {
       $sqlite = new PDO('sqlite:em.sqlite');
   }
   catch (PDOException $e) {
       echo 'Connection failed: ' . $e->getMessage();
   }
  
   if ($q == "") {
       $statement = $sqlite->prepare("SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions ORDER BY date DESC limit 1");
   } elseif ($q == "all") {
       $statement = $sqlite->prepare("SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions ORDER BY date DESC");
   } else {
       $statement = $sqlite->prepare("SELECT date,ball_1,ball_2,ball_3,ball_4,ball_5,star_1,star_2 FROM euro_millions WHERE date LIKE '%$q%' ORDER BY date DESC");
   }
  
   try {
       $statement->execute();
   }
   catch (PDOException $e) {
       echo "Statement failed: " . $e->getMessage();
       return false;
   }
  
   $result     = $statement->fetchAll();
   $numResults = count($result);
  
  
   if ($f == "xml") {
       header('Content-type: text/xml');
      
       echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
       echo ' <euromillions>'. "\n";
      
       foreach ($result as $row) {
           echo "  <drawn>
" . "   <date>" . $row['date'] . "</date>
" . "   <balls>" . $row['ball_1'] . " " . $row['ball_2'] . " " . $row['ball_3'] . " " . $row['ball_4'] . " " . $row['ball_5'] . "</balls>
" . "   <stars>" . $row['star_1'] . " " . $row['star_2'] . "</stars>
" . "  </drawn>" . "\n";
       }
       echo '</euromillions>';
      
   } elseif ($f == "json") {
      
       header('Content-type: application/json');
      
       print "{\r\n" . '   "drawns": [' . "\r\n";
       print "\r\n";
      
       $counter = 0;
       foreach ($result as $row) {
           if (++$counter == $numResults) {
               print "      {\r\n" . "\r\n         " . '"date":"' . $row['date'] . '", ' . "\r\n         " . '"balls":"' . $row['ball_1'] . " " . $row['ball_2'] . " " . $row['ball_3'] . " " . $row['ball_4'] . " " . $row['ball_5'] . '", ' . "\r\n         " . '"stars":"' . $row['star_1'] . " " . $row['star_2'] . '"' . "\r\n\r\n      }";
               print "\r\n";
           } else {
               print "      {\r\n" . "\r\n         " . '"date":"' . $row['date'] . '", ' . "\r\n\r\n         " . '"balls":"' . $row['ball_1'] . " " . $row['ball_2'] . " " . $row['ball_3'] . " " . $row['ball_4'] . " " . $row['ball_5'] . '", ' . "\r\n\r\n         " . '"stars":"' . $row['star_1'] . " " . $row['star_2'] . '"' . "\r\n\r\n      },\r\n\r\n";
               print "\r\n";
           }
       }
       print "\r\n   ]\r\n}";
      
   } else {
       header('Content-type: text/plain');
       foreach ($result as $row) {
           echo $row['date'] . " = " . $row['ball_1'] . " - " . $row['ball_2'] . " - " . $row['ball_3'] . " - " . $row['ball_4'] . " - " . $row['ball_5'] . " + " . $row['star_1'] . " - " . $row['star_2'];
           echo "\n";
       }
   }
  
?> 

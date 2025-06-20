<?php
include "../dbConnection.php";
include "queries.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $result = query();

            for ($i = 0; $i<count($result); $i++){
                echo "$i. ";
                for ($j = 0; $j < count($result[$i]); $j++){
                    $entry = $result[$i][$j];
                    echo " $entry; ";
                }
                echo "<br><br>";
            } 
        }

?>
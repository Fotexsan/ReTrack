<?php
include "../dbConnection.php";
include "queries.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //array mit Ergebnissen holen
    $result = query();

    $entryCount = count($result);
    
    //schauen ob Einträge gefunden wurden
    if ($entryCount){
        //Results header mit Eintrag Anzahl ausgeben
        echo    "<div class='results-header'>
                    <h2>Results</h2>
                    $entryCount Entries found
                </div>";

        //Kategorie und metrik holen
        $category = $_POST["category"];
        $metric = $_POST["metric"];

        //tabelle
        echo "<table>";
        echo "<tr><th>#</th>";

        //tabelle head bestimmen
        //timeCol für Zeitangabe, percentCol für % Zeichen
        switch($category){
            case "song":
                echo "<th>Song</th>";
                echo "<th>Artist</th>";
                echo "<th>Album</th>";
                $timeCol = 3;
                $percentCol = 5;
                break;
            case "album":
                echo "<th>Album</th>";
                echo "<th>Artist</th>";
                $timeCol = 2;
                $percentCol = 4;
                break;
            case "artist":
                echo "<th>Artist</th>";
                $timeCol = 1;
                $percentCol = 3;
        }

        switch($metric){
            case "mPlayed":
                echo "<th>Total Plays</th>";
                break;
            case "mTime":
                echo "<th>Total Time</th>";
                break;  
            case "mfPlayed":
                echo "<th>Total Plays</th>";
                echo "<th>Fully Played</th>";
                echo "<th>Percentage</th>";
                break;
            case "mSkipped":
                echo "<th>Total Plays</th>";
                echo "<th>Skipped</th>";
                echo "<th>Percentage</th>";
                break;
            case "mShuffle":
                echo "<th>Total Plays</th>";
                echo "<th>Shuffle Plays</th>";
                echo "<th>Percentage</th>";
                break;
            case "mDirect":
                echo "<th>Total Plays</th>";
                echo "<th>Direct Plays</th>";
                echo "<th>Percentage</th>";
        }
        echo "</tr>";

        //Daten Zeilenweise ausgeben
        for ($i = 0; $i<$entryCount; $i++){
            $counter = $i + 1;
            echo "<tr>";
            echo "<td>$counter.</td>";
            for ($j = 0; $j < count($result[$i]); $j++){
                $entry = $result[$i][$j];

                //abhängig von perecntCol % hinter Daten schreiben
                if ($j == $percentCol){
                    echo "<td>$entry%</td>";
                }
                //abhängig von timeCol Daten umrechenen und mit Einheit ausgeben
                elseif($j == $timeCol && $metric == "mTime"){
                    if ($entry >= 3600000){
                        $entry = round($entry / 3600000, 2);
                        $time = "h";
                    }
                    elseif ($entry >= 60000){
                        $entry = round($entry / 60000, 2);
                        $time = "min";
                    }
                    elseif ($entry >= 60000){
                        $entry = round($entry / 60000, 2);
                        $time = "min";
                    }
                    elseif ($entry >= 1000){
                        $entry = round($entry / 1000, 2);
                        $time = "s";
                    }
                    else{
                        $time = "ms";
                    }
                    echo "<td>$entry $time</td>";
                }
                else{
                    echo "<td>$entry</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";       
    }
    else{
        //ausgabe wenn kein Einträge gefunden wurden
        echo "No entries found";
    }
    
}

?>
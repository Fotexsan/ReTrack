<?php
    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        //hole accountId und username aus der Session
        $accountId = $_SESSION['id'];
        $username = $_SESSION['username'];
    } else {
        //wenn Nutzer nicht eingeloggt wird dieser zur login Seite weitergeleitet
        $_SESSION['info'] = "Upload";
        header("Location: login.php");
    }

    //so können echos angezeigt werden bevor der gesamte php code fertig ist
    while (ob_get_level()) {
        ob_end_flush();
    }
    ob_implicit_flush(true);

    include "logic/dbConnection.php";
    include "logic/saveSongData.php";

    //Maximale execution Zeit erhöhen, um timeout vorzubeugen
    ini_set('max_execution_time', 600);

    $songcounter = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTrack</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/file.css"> 
</head>
<body>

    <!--Navigationsbar -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link active">File Upload</a>
            <a href="help.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    echo
                    "<div class='dropdown'>
                        <button class='dropbtn'>$username</button>
                        <div class='dropdown-content'>
                            <a href='logic/account/logout.php' class='logout'>Log out</a>
                        </div>
                    </div>";
                }
                else{
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>

    <!--File Upload -->
    <div class="form-box">
        <h1>Upload your Spotify Data</h1>
        <p class="not-centered">Select the .json files from your downloaded Spotify data.<br> If you are not sure what to upload click <a href="help.php">here</a> for help.</p>
        
        <!-- Daten werden an sich selbst geschickt-->
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="Data">Choose JSON files</label>
            <input type="file" id="Data" name="Data[]" accept=".json" multiple required>
                
            <input class="form-submit" type="submit" value="Upload">
        </form>

        <p>
        <?php
        //Datenverarbeitung
            if (isset($_FILES['Data'])){
                $dataCount = count($_FILES['Data']['tmp_name']);

                //Ausgabe wieviele Dateien gelesen werden müssen.
                if ($dataCount == 1){
                    echo "1 file has to be read... <br><br>";
                }
                else{
                    echo " $dataCount files have to be read...<br><br>";
                }
                
                //Datenbank Verbindung herstellen
                $conn = connect();

                //Dateieneieinlesen
                for($i = 0; $i<$dataCount; $i++){
                
                    $filename = $_FILES['Data']['tmp_name'][$i];

                    //Funktion, um die Daten einer Datei zu lesen und zu speichern
                    saveSongData($filename, $conn, $accountId);
                    
                    //Ausgabe, um zu zeigen welche Dateien schon fertig sind
                    $filename = $_FILES['Data']['name'][$i];
                    echo "$filename done<br>";
                    flush();
                }

                //Datenbank Verbindung schließen
                $conn->close();
            
                echo "<br>";
                
                //Nachricht um zu zeigen, dass der upload Vorgang fertig ist
                echo "Finished! You uploaded <strong> $songcounter </strong> entries <br>";
                echo 'Click <a href="stats.php">here</a> to start getting your stats'; 
            }
        ?>
        </p>
    </div>
</body>
</html>
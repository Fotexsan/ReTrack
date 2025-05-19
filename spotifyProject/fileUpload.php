<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action ="" method="POST" enctype="multipart/form-data">
        <label for="Data">Your Spotify Data</label>
        <input type="file" id="Data" name="Data[]" webkitdirectory>
        <input type="submit"></input>
    </form>

    <?php

    $songcounter = 0;

    //Maximale execution Zeit erhöhen, um timeout vorzubeugen
    ini_set('max_execution_time', 300);

    //Funktion um Daten aus JSON in die Datenbank zu übertragen
    function saveData($filename, $conn, $accountId){
        // Datei einlesen
        $json = file_get_contents($filename);
        $data = json_decode($json, true);

        //alle Einträge der JSON durchgehen
        foreach ($data as $entry) {

            //Podcasts und Songs ohne Namen werden gefiltert
            if ($entry['master_metadata_track_name'] !== ''){

                //timestamp in richtiges Format bringen
                $timestamp = new DateTime($entry["ts"]);
                $ts = $timestamp->format('Y-m-d H:i:s');    

                //restliche Daten werden aus der JSON extrahiert
                $platform = $conn->real_escape_string($entry["platform"]);
                $ms = $entry["ms_played"];
                $country = $entry["conn_country"];
                $song = $conn->real_escape_string($entry["master_metadata_track_name"]);
                $artist = $conn->real_escape_string($entry["master_metadata_album_artist_name"]);
                $album = $conn->real_escape_string($entry["master_metadata_album_album_name"]);
                $uri = $entry["spotify_track_uri"];
                $start = $entry["reason_start"];
                $end = $entry["reason_end"];
                $shuffle = (int)$entry["shuffle"];
                $offline = (int)$entry["offline"];
                $incognito = (int)$entry["incognito_mode"];
                
                //Daten in Datenbank eintragen
                $sql = "INSERT INTO songData (
                accountId, ts, platform, ms_played, conn_country, master_metadata_track_name, master_metadata_album_artist_name,
                master_metadata_album_album_name, spotify_track_uri, reason_start, reason_end, shuffle, offline, incognito_mode) 
                VALUES ('$accountId', '$ts', '$platform', $ms, '$country', '$song', '$artist', '$album', '$uri', '$start', '$end', $shuffle, $offline, $incognito)";

                if (!$conn->query($sql)) {
                    echo "Fehler bei Song: " . $conn->error . "<br>";
                }

                $GLOBALS["songcounter"]++;
            }
        }
    }



    if (isset($_FILES['Data'])){
        $servername = "localhost";
        $username = "root";
        $passwort = "";

        //Verbindung zum DB-Server herstellen
        $conn = new mysqli($servername, $username, $passwort);
        
        //Verbindung überprüfen
        if ($conn->connect_error){
        die("Verbindung fehlgeschlagen: ".$conn->connect_error); //Exit + Fehlermeldung
        }
        //Datenbank erstellen (wird zur login Page bewegt)
        $sql = "CREATE DATABASE IF NOT EXISTS SpotifyStats";
        if ($conn->query($sql) === FALSE){
        echo "Fehler beim Erstellen der Datenbank". $conn->error . "<br>";
        }
        
        //Wähle die eben erstellte Datenbank aus
        $conn->select_db("SpotifyStats");

        //User Tabelle erstellen (wird zur login Page bewegt)
        $sql = "CREATE TABLE IF NOT EXISTS user(
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30),
        email VARCHAR(50),
        password VARCHAR(50),
        registrierungsdatum TIMESTAMP
        )";

        if($conn->query($sql) === TRUE){
        } else{
         echo "Fehler beim Erstellen der Tabelle". $conn->error . "<br>";
        }

        //songData Tabelle erstellen
        $sql = "CREATE TABLE IF NOT EXISTS songData(
        songId INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        accountId INT UNSIGNED,
        ts TIMESTAMP,
        platform VARCHAR(200),
        ms_played INT,
        conn_country VARCHAR(2),
        master_metadata_track_name VARCHAR(200),
        master_metadata_album_artist_name VARCHAR(200),
        master_metadata_album_album_name VARCHAR(200),
        spotify_track_uri VARCHAR(36),
        reason_start VARCHAR(9),
        reason_end VARCHAR(28),
        shuffle BOOLEAN,
        offline BOOLEAN,
        incognito_mode BOOLEAN,
        FOREIGN KEY (accountId) REFERENCES user(id) ON DELETE CASCADE
        )";

        if($conn->query($sql) === TRUE){
        } else{
         echo "Fehler beim Erstellen der Tabelle". $conn->error . "<br>";
        }

        $sql = "ALTER TABLE user AUTO_INCREMENT = 1";
        if($conn->query($sql) === TRUE){
        } else{
         echo "Fehler beim Erstellen der zurücksetzten der ID". $conn->error . "<br>";
        }

        $sql = "ALTER TABLE songdata AUTO_INCREMENT = 1";
        if($conn->query($sql) === TRUE){
        } else{
         echo "Fehler beim Erstellen der zurücksetzten der ID". $conn->error . "<br>";
        }

        $username = 'maxmustermann';
        $email = 'max@example.com';
        $password = 'geheim';

        $sql = "INSERT INTO user (username, email, password)
                VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Neuer User erfolgreich eingefügt<br>";
            $accountId = $conn->insert_id;
        } else {
            echo "Fehler beim Einfügen des Users: " . $conn->error . "<br>";
        }

        while (ob_get_level()) {
            ob_end_flush();
        }

        ob_implicit_flush(true);

        flush();


        $dataCount = count($_FILES['Data']['tmp_name']) - 1;


        for($i = 1; $i<=$dataCount; $i++){
            $filename = $_FILES['Data']['tmp_name'][$i];
            saveData($filename, $conn, $accountId);
            echo "$i/$dataCount done<br>";
            flush();
        }

        echo "Finished! You uploaded $songcounter entries";


        //saveData($filename, $conn, 2); //accountId muss noch von irgendwo kommen
        //echo $_FILES['Data']['name'][1]; //
        $conn->close();
    }

    

        /*
        array(6) 
        { 
            ["name"]=> array(14) 
            { 
                [0]=> string(40) "ReadMeFirst_ExtendedStreamingHistory.pdf" 
                [1]=> string(40) "Streaming_History_Audio_2015-2018_0.json" 
                [2]=> string(40) "Streaming_History_Audio_2018-2019_1.json" 
                [3]=> string(40) "Streaming_History_Audio_2019-2020_2.json" 
                [4]=> string(40) "Streaming_History_Audio_2020-2021_4.json" 
                [5]=> string(35) "Streaming_History_Audio_2020_3.json" 
                [6]=> string(40) "Streaming_History_Audio_2021-2022_6.json" 
                [7]=> string(35) "Streaming_History_Audio_2021_5.json" 
                [8]=> string(40) "Streaming_History_Audio_2022-2023_7.json" 
                [9]=> string(40) "Streaming_History_Audio_2023-2024_9.json" 
                [10]=> string(35) "Streaming_History_Audio_2023_8.json" 
                [11]=> string(41) "Streaming_History_Audio_2024-2025_11.json" 
                [12]=> string(36) "Streaming_History_Audio_2024_10.json" 
                [13]=> string(38) "Streaming_History_Video_2023-2025.json" 
            } 
            ["full_path"]=> array(14) 
            { 
                [0]=> string(75) "Spotify Extended Streaming History/ReadMeFirst_ExtendedStreamingHistory.pdf" 
                [1]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2015-2018_0.json" 
                [2]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2018-2019_1.json" 
                [3]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2019-2020_2.json" 
                [4]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2020-2021_4.json" 
                [5]=> string(70) "Spotify Extended Streaming History/Streaming_History_Audio_2020_3.json" 
                [6]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2021-2022_6.json" 
                [7]=> string(70) "Spotify Extended Streaming History/Streaming_History_Audio_2021_5.json" 
                [8]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2022-2023_7.json" 
                [9]=> string(75) "Spotify Extended Streaming History/Streaming_History_Audio_2023-2024_9.json" 
                [10]=> string(70) "Spotify Extended Streaming History/Streaming_History_Audio_2023_8.json" 
                [11]=> string(76) "Spotify Extended Streaming History/Streaming_History_Audio_2024-2025_11.json" 
                [12]=> string(71) "Spotify Extended Streaming History/Streaming_History_Audio_2024_10.json" 
                [13]=> string(73) "Spotify Extended Streaming History/Streaming_History_Video_2023-2025.json" 
            } 
            ["type"]=> array(14) 
            { 
                [0]=> string(15) "application/pdf" 
                [1]=> string(16) "application/json" 
                [2]=> string(16) "application/json" 
                [3]=> string(16) "application/json" 
                [4]=> string(16) "application/json" 
                [5]=> string(16) "application/json" 
                [6]=> string(16) "application/json" 
                [7]=> string(16) "application/json" 
                [8]=> string(16) "application/json" 
                [9]=> string(16) "application/json" 
                [10]=> string(16) "application/json" 
                [11]=> string(16) "application/json" 
                [12]=> string(16) "application/json" 
                [13]=> string(16) "application/json" 
            } 
            ["tmp_name"]=> array(14) 
            { 
                [0]=> string(24) "F:\xampp\tmp\php80D5.tmp" 
                [1]=> string(24) "F:\xampp\tmp\php80E5.tmp" 
                [2]=> string(24) "F:\xampp\tmp\php8125.tmp" 
                [3]=> string(24) "F:\xampp\tmp\php8155.tmp" 
                [4]=> string(24) "F:\xampp\tmp\php8185.tmp" 
                [5]=> string(24) "F:\xampp\tmp\php81B5.tmp" 
                [6]=> string(24) "F:\xampp\tmp\php81E4.tmp" 
                [7]=> string(24) "F:\xampp\tmp\php8205.tmp" 
                [8]=> string(24) "F:\xampp\tmp\php8225.tmp" 
                [9]=> string(24) "F:\xampp\tmp\php8255.tmp" 
                [10]=> string(24) "F:\xampp\tmp\php8285.tmp"
                [11]=> string(24) "F:\xampp\tmp\php82A5.tmp" 
                [12]=> string(24) "F:\xampp\tmp\php82D5.tmp" 
                [13]=> string(24) "F:\xampp\tmp\php82F5.tmp" 
            } 
            ["error"]=> array(14) 
            { 
                [0]=> int(0) 
                [1]=> int(0) 
                [2]=> int(0) 
                [3]=> int(0) 
                [4]=> int(0) 
                [5]=> int(0) 
                [6]=> int(0) 
                [7]=> int(0) 
                [8]=> int(0) 
                [9]=> int(0) 
                [10]=> int(0)
                [11]=> int(0) 
                [12]=> int(0) 
                [13]=> int(0) 
            } 
            ["size"]=> array(14) 
            { 
                [0]=> int(1618128) 
                [1]=> int(12735622) 
                [2]=> int(12759042) 
                [3]=> int(12775934) 
                [4]=> int(12783601) 
                [5]=> int(12777509) 
                [6]=> int(12775465) 
                [7]=> int(12770624) 
                [8]=> int(12797941) 
                [9]=> int(12841211) 
                [10]=> int(12838836)
                [11]=> int(11068443) 
                [12]=> int(12827331) 
                [13]=> int(69995) 
            } 
        }
        */


    ?>

</body>
</html>
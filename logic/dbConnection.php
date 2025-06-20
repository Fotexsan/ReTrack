<?php
function connect(){
    $servername = "localhost";
    $adminName = "root";
    $passwort = "";

    //Verbindung zum DB-Server herstellen
    $conn = new mysqli($servername, $adminName, $passwort);
    
    //Verbindung überprüfen
    if ($conn->connect_error){
        die("Verbindung fehlgeschlagen: ".$conn->connect_error); //Exit + Fehlermeldung
    }

    //Datenbank erstellen
    $sql = "CREATE DATABASE IF NOT EXISTS SpotifyStats";
    if ($conn->query($sql) === FALSE){
        die("Fehler beim erstellen der Datenbank: ".$conn->connect_error);
    }
    
    //Wähle die eben erstellte Datenbank aus
    $conn->select_db("SpotifyStats");

    //User Tabelle erstellen
    $sql = "CREATE TABLE IF NOT EXISTS user(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30),
    email VARCHAR(50),
    password VARCHAR(50),
    registrierungsdatum TIMESTAMP
    )";

    if($conn->query($sql) === FALSE){
        die("Fehler bei Tabellenerstellung: ".$conn->connect_error);
    }

    //songData Tabelle erstellen, wobei 'accountId, ts, ms_played, spotify_track_uri' genutzt wird um Einträge eindeutig zu identifizieren
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
    reason_start VARCHAR(30),
    reason_end VARCHAR(30),
    shuffle BOOLEAN,
    skipped BOOLEAN,
    offline BOOLEAN,
    incognito_mode BOOLEAN,
    FOREIGN KEY (accountId) REFERENCES user(id) ON DELETE CASCADE,
    UNIQUE KEY unique_entry (accountId, ts, ms_played, spotify_track_uri)
    )";

    if($conn->query($sql) === FALSE){
        die("Fehler bei Tabellenerstellung: ".$conn->connect_error);
    }

    return $conn;
}
?>
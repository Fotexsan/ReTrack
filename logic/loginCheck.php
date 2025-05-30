<?php
session_start();

if (isset($_POST["email"]) && isset($_POST["password"])){
    //User Eingabe speichern
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];
    $servername = "localhost";
    $adminname = "root";
    $passwort = "";
    //Verbindung zum DB-Server herstellen
    $conn = new mysqli($servername, $adminname, $passwort);
    //Datenbank erstellen
    $sql = "CREATE DATABASE IF NOT EXISTS SpotifyStats";

    if ($conn->query($sql) === FALSE){
        echo "Fehler beim Erstellen der Datenbank". $conn->error;
        die();
    }
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
        echo "Fehler beim Erstellen der Tabelle". $conn->error;
        die();
    }

    //Checken ob User mit den Daten existiert
    $sql = "SELECT id, username 
    FROM user 
    WHERE email = '$userEmail' 
    AND password = '$userPassword' 
    LIMIT 1;";
    $result = $conn->query($sql);
    //bei erfolgreicher Anmeldung angemeldet zur homepage weiterleiten
    if ($result && $row = $result->fetch_assoc()) {
        $username = $row['username'];
        $userId = $row['id'];
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $userId;
        header("Location: ../homepage.php");
        die();
    }
    else{
        $_SESSION['error'] = 1;
    }
}
header("Location: ../login.php");
die();
?>
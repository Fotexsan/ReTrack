<?php
session_start();

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["rePassword"]) && isset($_POST["userName"])){
    //User Eingabe speichern
    $username = $_POST["userName"];
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];
    $userRePassword = $_POST["rePassword"];

    if ($userPassword == $userRePassword){
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

        //Checken ob Email schon in Gebrauch ist
        $sql = "SELECT COUNT(*) > 0
                FROM user
                WHERE email = '$userEmail';";
        $result = ($conn->query($sql))->fetch_row();
        if ($result[0]>0){
            $_SESSION["error"] = 2;
        }
        if (!isset($_SESSION["error"])){
            //Account erstellen
            $sql= "INSERT INTO user (username, email, password) 
            VALUES ('$username', '$userEmail', '$userPassword')";
            if (!$conn->query($sql)) {
                echo "Fehler beim Daten übernehmen" . $conn->error . "<br>";
                die();
            }
            
            $userId = $conn->insert_id;
            //Angemeldet zur homepage weiterleiten
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $userId;
            header("Location: ../homepage.php");
            die();
        }
    }
    else{
        $_SESSION["error"] = 1;
    }
}
header("Location: ../createAccount.php");
die();
?>
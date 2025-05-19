<?php
    $exist = 1;
    if (isset($_POST["email"]) && isset($_POST["password"])){
        //User Eingabe speichern
        $userEmail = $_POST["email"];
        $userPassword = $_POST["password"];

        $servername = "localhost";
        $username = "root";
        $passwort = "";

        //Verbindung zum DB-Server herstellen
        $conn = new mysqli($servername, $username, $passwort);

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
        $sql = "SELECT COUNT(*) > 0
                FROM user
                WHERE email = '$userEmail'
                AND password = '$userPassword';";

        $result = ($conn->query($sql))->fetch_row();
        $exist = $result[0];

        //weiterleiten wenn anmeldung erfolgreich
        if ($exist){
            header("Location: https://google.com"); //soll auf homepage verweisen
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotistats</title>
</head>
<body>
    <h1>Login</h1>
    <form action ="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Your Spotify Data</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit"></input>
    </form>
    <?php
            //Nachricht anzeigen falls anmeldung fehlgeschlagen ist
            if ($exist == 0){
                echo "Email or password is wrong";
            }
        ?>
    <p>Don't have an account? Create one</p>
</body>
</html>
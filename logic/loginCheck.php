<?php
session_start();
include "dbConnection.php";

if (isset($_POST["email"]) && isset($_POST["password"])){
    //User Eingabe speichern
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];

    //Verbindung zum DB-Server herstellen
    $conn = connect();

    //Checken ob User mit den Daten existiert
    $sql = "SELECT id, username 
    FROM user 
    WHERE email = '$userEmail' 
    AND password = '$userPassword' 
    LIMIT 1;";

    $result = $conn->query($sql);

    $conn->close();

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
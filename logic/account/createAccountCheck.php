<?php
session_start();
include "../dbConnection.php";

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["rePassword"]) && isset($_POST["username"])){
    //User Eingabe speichern
    $username = $_POST["username"];
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];
    $userRePassword = $_POST["rePassword"];

    if ($userPassword == $userRePassword){
        //Verbindung zum DB-Server herstellen
        $conn = connect();
        
        //Checken ob Email schon in Gebrauch ist
        $sql = "SELECT COUNT(*) > 0
                FROM user
                WHERE email = '$userEmail';";

        $result = ($conn->query($sql))->fetch_row();

        //kein User gefunden, wird in Session gespeichert
        if ($result[0]>0){
            $_SESSION["error"] = 2;
        }
        else{
            //Account erstellen
            $sql= "INSERT INTO user (username, email, password) 
            VALUES ('$username', '$userEmail', '$userPassword')";
            if (!$conn->query($sql)) {
                echo "Fehler beim Daten übernehmen" . $conn->error . "<br>";
                die();
            }
            
            //hole userId von neu angelegtem User
            $userId = $conn->insert_id;

            $conn->close();

            //Angemeldet zur homepage weiterleiten
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $userId;

            header("Location: ../../homepage.php");
            die();
        }
        $conn->close();
    }
    else{
        $_SESSION["error"] = 1;
    }
}
//Bei Fehler zurück zur createAccount Seite leiten
header("Location: ../../createAccount.php");
die();
?>
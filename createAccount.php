<?php
    session_start();

    $error = 0;
    
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["rePassword"]) && isset($_POST["userName"])){
        //User Eingabe speichern

        $userName = $_POST["userName"];
        $userEmail = $_POST["email"];
        $userPassword = $_POST["password"];
        $userRePassword = $_POST["rePassword"];

        if ($userPassword == $userRePassword){
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

            //Checken ob Email schon in Gebrauch ist
            $sql = "SELECT COUNT(*) > 0
                    FROM user
                    WHERE email = '$userEmail';";

            $result = ($conn->query($sql))->fetch_row();

            if ($result[0]>0){
                $error = 2;
            }

            //Checken ob Username schon in Gebrauch ist
            $sql = "SELECT COUNT(*) > 0
                    FROM user
                    WHERE username = '$userName';";

            $result = ($conn->query($sql))->fetch_row();

            //weiterleiten wenn anmeldung erfolgreich
            if ($result[0]>0 && $error == 2){
                $error = 4;
            }
            elseif ($result[0]>0){
                $error = 3;
            }

            if ($error == 0){

                $sql= "INSERT INTO user (username, email, password) 
                VALUES ('$userName', '$userEmail', '$userPassword')";

                if (!$conn->query($sql)) {
                    echo "Fehler beim Daten übernehmen" . $conn->error . "<br>";
                    die();
                }

                $userId = $conn->insert_id;

                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $userName;
                $_SESSION['id'] = $userId;
                header("Location: homepage.php");
                die();
            }
        }
        else{
            $error = 1;
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
    <h1>Create account</h1>
    <form action ="" method="POST">
        <label for="userName">Username:</label>
        <input type="text" id="userName" name="userName" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="rePassword">repeat Password:</label>
        <input type="password" id="rePassword" name="rePassword" required>
        <input type="submit"></input>
    </form>
    <?php
            //Nachricht anzeigen falls anmeldung fehlgeschlagen ist
            switch ($error){
                case 1:
                    echo "Password and repeated password are different";
                    break;
                case 2:
                    echo "Email is already in use";
                    break;
                case 3:
                    echo "Username is already in use";
                    break;
                case 4:
                    echo "Email and username are already in use";
            }
        ?>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
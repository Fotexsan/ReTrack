<?php
    session_start();

    $error = 1;
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
        $sql = "SELECT id, username 
        FROM user 
        WHERE email = '$userEmail' 
        AND password = '$userPassword' 
        LIMIT 1;";

        $result = $conn->query($sql);

        //bei erfolgreicher Anmeldung angemeldet zur homepage weiterleiten
        if ($result && $row = $result->fetch_assoc()) {
            $userName = $row['username'];
            $userId = $row['id'];

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $userName;
            $_SESSION['id'] = $userId;
            header("Location: homepage.php");
            die();
        }
        else{
            $error = 0;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotistats</title>
    <link rel="stylesheet" href="./css/reTrack.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="explanation.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <a href="account.php" class="nav-link active">Account</a>
        </div>
    </nav>


    <div class="form">
        <h1>Login</h1>
        <form action ="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
            <br>
            <label for="password">Password: </label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br>
            <input class="SubmitButton" type="submit" value="Login"></input>
        </form>
        <?php
            //Nachricht anzeigen falls anmeldung fehlgeschlagen ist
            if ($error == 0){
                echo '<p class="error">Email or password is wrong</p>';
            }
        ?>
        <p>Don't have an account? <a href="createAccount.php">Create account</a></p>
    </div>
</body>
</html>
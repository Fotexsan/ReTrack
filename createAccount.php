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

            if ($error == 0){
                //Account erstellen
                $sql= "INSERT INTO user (username, email, password) 
                VALUES ('$userName', '$userEmail', '$userPassword')";

                if (!$conn->query($sql)) {
                    echo "Fehler beim Daten übernehmen" . $conn->error . "<br>";
                    die();
                }
                
                $userId = $conn->insert_id;

                //Angemeldet zur homepage weiterleiten
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
    <link rel="stylesheet" href="./css/reTrack.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="help.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    echo
                    "<div class='dropdown'>
                        <button class='dropbtn'>$username</button>
                        <div class='dropdown-content'>
                            <a href='logic/logout.php' class='logout'>Log out</a>
                        </div>
                    </div>";
                }
                else{
                    echo "<a href='login.php' class='nav-link active'>Log in</a>";
                }
            ?>
        </div>
    </nav>



    <div class="form-box">
        <h1 class="form-h1">Create Account</h1>
        <form action="" method="POST">
            <label for="userName">Username:</label>
            <input type="text" id="userName" name="userName" placeholder="Username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <label for="rePassword">Repeat password:</label>
            <input type="password" id="rePassword" name="rePassword" placeholder="Repeat password" required>

            <input class="form-submit" type="submit" value="Create">
        </form>

        <?php
            switch ($error){
                case 1:
                    echo "<p class='error'>Password and repeated password are different</p>";
                    break;
                case 2:
                    echo "<p class='error'>Email is already in use</p>";
            }
        ?>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
<?php
    //wenn bereits eingeloggt ist auf homepage weiterleiten 
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
        header("Location: homepage.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTrack</title>
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <!--Navigationsbar -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Get Started</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
        </div>
        <div class="nav-right">
            <a href="login.php" class="nav-link active">Log in</a>
        </div>
    </nav>

    <!-- Login Formularfeld -->
    <div class="form-box">
        <h1 class="form-h1">Login</h1>

        <!-- Eingabe wird auf loginCheck.php verarbeitet-->
        <form action ="logic/account/loginCheck.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
            <label for="password">Password: </label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input class="form-submit" type="submit" value="Login"></input>
        </form>

        <?php
            //Nachricht anzeigen falls anmeldung fehlgeschlagen ist
            if (isset($_SESSION["error"])){
                echo "<p class='error'>Email or password is wrong</p>";
                unset($_SESSION["error"]);
            }
            elseif(isset($_SESSION["info"])){
                if ($_SESSION["info"] == "Upload"){
                    echo "<p class='error'>You need to login before you can upload files</p>";
                }
                elseif ($_SESSION["info"] == "Stats"){
                    echo "<p class='error'>You need to login before you can see your stats</p>";
                }
                unset($_SESSION["info"]);
            }
        ?>
        <p>Don't have an account? <a href="createAccount.php">Create account</a></p>
    </div>
</body>
</html>
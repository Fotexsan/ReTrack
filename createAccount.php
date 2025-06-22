<?php
    session_start();
    //wenn bereits eingeloggt ist auf homepage weiterleiten 
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
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="help.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <a href='login.php' class='nav-link active'>Log in</a>
        </div>
    </nav>


    <!-- Create Account Formularfeld -->
    <div class="form-box">
        <h1 class="form-h1">Create Account</h1>

        <!-- Eingabe wird auf createAccountCheck.php verarbeitet-->
        <form action="logic/account/createAccountCheck.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <label for="rePassword">Repeat password:</label>
            <input type="password" id="rePassword" name="rePassword" placeholder="Repeat password" required>

            <input class="form-submit" type="submit" value="Create">
        </form>

        <?php
            //Nachricht anzeigen falls Account Erstellung fehlgeschlagen ist
            if (isset($_SESSION["error"])){
                $error = $_SESSION["error"];
                switch ($error){
                    case 1:
                        echo "<p class='error'>Password and repeated password are different</p>";
                        break;
                    case 2:
                        echo "<p class='error'>Email is already in use</p>";
                }
                unset($_SESSION["error"]);
            }
        ?>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
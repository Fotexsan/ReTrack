<?php 
session_start(); 
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {     
    //hole accountId und username aus der Session
    $username = $_SESSION['username'];
    $userid = $_SESSION['id'];
}
?>

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>ReTrack</title>     
    <link rel="stylesheet" href="css/main.css"> 
</head> 
<body>    
     <!--Navigationsbar -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link active">Get Started</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
        </div>
        <div class="nav-right">
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    //Wenn User eingeloggt zeige Accountname mit Dropdown menü
                    echo
                    "<div class='dropdown'>
                        <button class='dropbtn'>$username</button>
                        <div class='dropdown-content'>
                            <a href='logic/account/logout.php' class='logout'>Log out</a>
                        </div>
                    </div>";
                }
                else{
                    //Sonst zeige Login button
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>


    <div class="content">

        <h1>Welcome to ReTrack</h1>
        <p>See what Spotify Wrapped doesn't tell you.</p>
        <p>Have you ever wondered which song you have listend to for the longest total time? Or which song you have skipped the most? 
            Are you impatient and can't wait until December for Spotify Wrapped? For all that and more, ReTrack has got you covered!
        </p>
        
        <p>Click <a href="fileUpload.php">here</a> to get to the file upload.</p>  

        <h2>How it works</h2>
        <ol>
            <li>Download your Spotify Data</li>
            <li>Create a ReTrack account</li>
            <li>Upload your Spotify Data</li>
            <li>View your stats</li>
        </ol>

        <p>Get right into it: <a href="createAccount.php">Create account</a></p>
        <h2>What files do I upload?</h2>
        <p>After getting your extended streaming history from Spotify, look for a folder named "Spotify_Extended_Streaming_History".
            That folder should contain all the relevant .json files.
        </p>
    </div>
</body> 
</html>
<?php 
session_start(); 
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {     
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
    <link rel="stylesheet" href="css/reTrack.css"> 
</head> 
<body>     
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link active">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="explanation.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <a href="account.php" class="nav-link">Account</a>
        </div>
    </nav>


    <div class="content-box">

        <h1>Welcome to ReTrack</h1>
        <p>See what Spotify Wrapped doesn't tell you</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reprehenderit consequuntur laboriosam quas, exercitationem consequatur debitis nostrum nemo aperiam laborum dolorum dignissimos aspernatur, sequi numquam nihil in, delectus officia illo obcaecati?</p>
        
        <p>Click <a href="fileUpload.php">here</a> to get to the file upload.</p>  

        <h2>How it works</h2>
        <ol>
            <li>Download your Spotify Data</li>
            <li>Create a ReTrack account</li>
            <li>Upload your Spotify Data</li>
            <li>View your stats</li>
        </ol>

        <p>Get right into it: <a href="createAccount.php">Create account</a></p>
    </div>
</body> 
</html>
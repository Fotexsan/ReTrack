<?php
    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $accountId = $_SESSION['id'];
        $username = $_SESSION['username'];
    } else {
        //echo "Please log in first to see this page.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTrack</title>
    <link rel="stylesheet" href="./css/reTrack.css">
</head>
<body>
     <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="help.php" class="nav-link active">Help</a>
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
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>

    <div class="content">
        <h1>Help</h1>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reprehenderit consequuntur laboriosam quas, exercitationem consequatur debitis nostrum nemo aperiam laborum dolorum dignissimos aspernatur, sequi numquam nihil in, delectus officia illo obcaecati?</p>
    </div>

</body>
</html>
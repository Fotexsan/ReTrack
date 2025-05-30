<?php
include "logic/queries.php";
include "logic/dbConnection.php";

$queries = getQueries();
-
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $accountId = $_SESSION['id'];
    $username = $_SESSION['username'];
} else {
    $_SESSION['info'] = "Stats";
    header("Location: login.php");
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
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link active">Stats</a>
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
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>

    <h1>:3</h1>
    <form action ="" method="POST">
        <label for="selectedQuery">Select a query:</label>
        <select name="selectedQuery" id="selectedQuery">
            <option value="-1" selected>Choose a query!</option>
            <?php 
                $i=0; 
                foreach($queries as $query){
                    $name = $queries[$i][1];
                    echo "<option value='$i'>$name</option>";
                    $i++;
                }
            ?>
        </select>
        <input type="submit"></input>
    </form>
</body>
</html>

<?php
$conn = connect();

//gette den value aus dem Drop down menü auf $selectedQuery
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['selectedQuery'] != "-1") {
    $sql = $queries[$_POST['selectedQuery']][0];//[$_POST['selectedQuery']]; 
    switch ($_POST['selectedQuery']){
        case 0:
            $sql = sprintf($sql,"master_metadata_album_artist_name",$_SESSION['id'],"master_metadata_album_artist_name");
            break;
        case 1:
            $sql = sprintf($sql,$_SESSION['id']);
            break;
    }
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0 ) {
        echo $sql . "<br><br>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //$timeInHours = round($row["SUM(ms_played)"] / 3600000,2);
            //echo "You listened to " .$row["master_metadata_album_artist_name"]. " for ". $timeInHours. " hours." . "<br>";
            echo "You listened to " .$row["master_metadata_album_artist_name"]. " for ". " hours." . "<br>";
        }
    } 
    else {
        echo "<br> 0 results";
    }
    $conn->close();
} 
?>
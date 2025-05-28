<?php
require "queries.php";
$queries = getQueries();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotistats</title>
</head>
<body>
    <h1>:3</h1>
    <form action ="" method="POST">
        <label for="selectedQuery">Select a query:</label>
        <select name="selectedQuery" id="selectedQuery">
            <option value="-1" selected>Choose a query!</option>
            <?php $i=0; foreach($queries as $query){
                $name = $queries[$i][1];
                echo "<option value='$i'>$name</option>";
                $i++;
            } ?>
        </select>
        <input type="submit"></input>
    </form>
</body>
</html>

<?php
session_start();

$servername = "localhost";
$username = "root";
$passwort = "";

$conn = new mysqli($servername, $username, $passwort);

 //Verbindung überprüfen
if ($conn->connect_error){
die("Verbindung fehlgeschlagen: ".$conn->connect_error); //Exit + Fehlermeldung
}

//Wähle die eben erstellte Datenbank aus
$conn->select_db("SpotifyStats");


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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotistats</title>
</head>
<body>
    <h1>Create account</h1>
    <form action ="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <label for="selectedQuery">Select a query:</label>
        <select name="selectedQuery" id="selectedQuery">
            <option value="placeholder" selected>Choose a query!</option>
            <!--
            <?php foreach($countries as $country){ ?>
                <option value="<?php echo $country['country_code'];?>"><?php echo $country['country_name'];?></option>
             <?php } ?>
            -->
            <option value="SELECT SUM(ms_played), master_metadata_album_artist_name FROM songdata WHERE accountId =19 GROUP BY master_metadata_album_artist_name ORDER BY `SUM(ms_played)` DESC">
                How much time did I spend listening to my top artists?
            </option>
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['selectedQuery'] != "placeholder") {
    $sql = $_POST['selectedQuery']; 
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo $sql . "<br><br>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $timeInHours = round($row["SUM(ms_played)"] / 3600000,2);
            echo "You listened to " .$row["master_metadata_album_artist_name"]. " for ". $timeInHours. " hours." . "<br>";
        }
        echo $result;
    } 
    else {
        echo "0 results";
    }
    $conn->close();
} 
?>
<?php
include "../../dbConnection.php";

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    //hole accountId aus der Session
    $id = $_SESSION['id'];
} else {
    //wenn Nutzer nicht eingeloggt wird dieser zur login Seite weitergeleitet
    header("Location: login.php");
    die();
}

//JSON header setzten
header('Content-Type: application/json');

//wenn keine Daten übergeben wurden wird, wird ein leeres JSON array zurückgegeben
if (!isset($_GET['term'])) {
    echo json_encode([]);
    die();
}

//Verbindung zu Datenbank herstellen
$conn = connect();

//Benutzereingabe speichern
$term = $_GET['term'];

//String richtig escapen, so stören Zeichen wie ' nicht
$term = $conn->real_escape_string($term);

//sql Befehl erstellen, der maximal 10 Zeilen holt, die einen ähnlichen Artistnamen haben
$sql = "SELECT DISTINCT master_metadata_album_artist_name 
        FROM songData 
        WHERE accountId = '$id' AND master_metadata_album_artist_name LIKE CONCAT('%', '$term', '%') 
        LIMIT 10";

//sql Befehl ausführen
$result = $conn->query($sql);

//suggestions Array initialisieren
$suggestions = [];

//Alle Zeilen, die gefunden wurden im suggestions Array speichern
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['master_metadata_album_artist_name'];
}

//JSON ANtwort mit suggestions Objekt zurückgeben
echo json_encode($suggestions);

//Datenbankverbindungschließen
$conn->close();
?>
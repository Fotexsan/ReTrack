<?php
require_once "../logic/dbConnection.php";

if (isset($_GET['query']) && isset($_GET['type'])) {
    $conn = connect();
    $query = $conn->real_escape_string($_GET['query']);
    $type = $_GET['type'];

    if ($type === 'title') {
        $sql = "SELECT DISTINCT master_metadata_track_name AS title, master_metadata_album_artist_name AS artist 
                FROM songData 
                WHERE master_metadata_track_name LIKE '%$query%' 
                LIMIT 10";
    } elseif ($type === 'artist') {
        $sql = "SELECT DISTINCT master_metadata_album_artist_name AS artist 
                FROM songData 
                WHERE master_metadata_album_artist_name LIKE '%$query%' 
                LIMIT 10";
    } elseif ($type === 'album') {
        $sql = "SELECT DISTINCT master_metadata_album_album_name AS album, master_metadata_album_artist_name AS artist 
                FROM songData 
                WHERE master_metadata_album_album_name LIKE '%$query%' 
                LIMIT 10";
    } else {
        echo json_encode([]);
        exit;
    }

    $result = $conn->query($sql);
    $suggestions = [];

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
}
?>

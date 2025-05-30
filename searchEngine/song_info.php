<?php
require_once "../logic/dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['selected_value']) && isset($_POST['selected_type'])) {
    $conn = connect();
    $type = $_POST['selected_type'];
    $value = $_POST['selected_value'];

    $sql = "";
    if ($type === 'title') {
        list($title, $artist) = explode('||', $value);
        $title = $conn->real_escape_string($title);
        $artist = $conn->real_escape_string($artist);
        $sql = "SELECT * FROM songData 
                WHERE master_metadata_track_name = '$title' 
                  AND master_metadata_album_artist_name = '$artist'";
    } elseif ($type === 'artist') {
        $artist = $conn->real_escape_string($value);
        $sql = "SELECT * FROM songData 
                WHERE master_metadata_album_artist_name = '$artist'";
    } elseif ($type === 'album') {
        list($album, $artist) = explode('||', $value);
        $album = $conn->real_escape_string($album);
        $artist = $conn->real_escape_string($artist);
        $sql = "SELECT * FROM songData 
                WHERE master_metadata_album_album_name = '$album' 
                  AND master_metadata_album_artist_name = '$artist'";
    } else {
        echo "<p>Ungültiger Suchtyp.</p>";
        exit;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Suchergebnisse:</h2>";
        echo "<table border='1' cellpadding='8'>";
        echo "<tr>
                <th>Song ID</th><th>Account ID</th><th>Timestamp</th><th>Platform</th>
                <th>ms_played</th><th>Country</th><th>Track Name</th><th>Artist</th><th>Album</th>
                <th>URI</th><th>Start Reason</th><th>End Reason</th>
                <th>Shuffle</th><th>Offline</th><th>Inkognito</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['songId']}</td>
                    <td>{$row['accountId']}</td>
                    <td>{$row['ts']}</td>
                    <td>{$row['platform']}</td>
                    <td>{$row['ms_played']}</td>
                    <td>{$row['conn_country']}</td>
                    <td>{$row['master_metadata_track_name']}</td>
                    <td>{$row['master_metadata_album_artist_name']}</td>
                    <td>{$row['master_metadata_album_album_name']}</td>
                    <td>{$row['spotify_track_uri']}</td>
                    <td>{$row['reason_start']}</td>
                    <td>{$row['reason_end']}</td>
                    <td>" . ($row['shuffle'] ? 'Ja' : 'Nein') . "</td>
                    <td>" . ($row['offline'] ? 'Ja' : 'Nein') . "</td>
                    <td>" . ($row['incognito_mode'] ? 'Ja' : 'Nein') . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Keine Ergebnisse gefunden.</p>";
    }

    $conn->close();
} else {
    echo "<p>Ungültige Anfrage.</p>";
}
?>

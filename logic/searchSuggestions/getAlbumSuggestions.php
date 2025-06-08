<?php
require_once __DIR__ . '/../dbConnection.php';

header('Content-Type: application/json');

$conn = connect();
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
$accountId = isset($_GET['accountId']) ? (int)$_GET['accountId'] : 0;

if ($accountId <= 0) {
    echo json_encode([]);
    exit;
}

// Normalisiere den Suchbegriff
$normalizedSearch = trim(preg_replace('/\s+/', ' ', strtolower($searchTerm)));

$stmt = $conn->prepare("SELECT DISTINCT master_metadata_album_album_name, master_metadata_album_artist_name 
                               FROM songData 
                               WHERE accountId = ?");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $album = $row['master_metadata_album_album_name'];
    $artist = $row['master_metadata_album_artist_name'];
    
    if ($album && $artist) {
        $normalizedAlbum = trim(preg_replace('/\s+/', ' ', strtolower($album)));
        $normalizedArtist = trim(preg_replace('/\s+/', ' ', strtolower($artist)));
        
        // Suche in Albumname UND Künstlername
        if (strpos(str_replace(' ', '', $normalizedAlbum), str_replace(' ', '', $normalizedSearch)) !== false ||
            strpos(str_replace(' ', '', $normalizedArtist), str_replace(' ', '', $normalizedSearch)) !== false) {
            
            $displayText = "$album - $artist";
            $suggestions[] = [
                'display' => $displayText,
                'album' => $album,
                'artist' => $artist
            ];
        }
    }
}

usort($suggestions, function($a, $b) use ($normalizedSearch) {
    $aAlbumPos = stripos($a['album'], $normalizedSearch);
    $aArtistPos = stripos($a['artist'], $normalizedSearch);
    $aPos = ($aAlbumPos !== false) ? $aAlbumPos : $aArtistPos;
    
    $bAlbumPos = stripos($b['album'], $normalizedSearch);
    $bArtistPos = stripos($b['artist'], $normalizedSearch);
    $bPos = ($bAlbumPos !== false) ? $bAlbumPos : $bArtistPos;
    
    return $aPos - $bPos;
});

$suggestions = array_slice($suggestions, 0, 10);

// Nur die Display-Texte für die Datalist zurückgeben
echo json_encode(array_column($suggestions, 'display'));
$conn->close();
?>
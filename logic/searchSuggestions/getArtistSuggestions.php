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

// Normalisiere den Suchbegriff: Kleinbuchstaben und entferne überflüssige Leerzeichen
$normalizedSearch = trim(preg_replace('/\s+/', ' ', strtolower($searchTerm)));

$stmt = $conn->prepare("SELECT DISTINCT master_metadata_album_artist_name 
                       FROM songData 
                       WHERE accountId = ?");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $artist = $row['master_metadata_album_artist_name'];
    if ($artist) {
        // Normalisiere den Künstlernamen für den Vergleich
        $normalizedArtist = trim(preg_replace('/\s+/', ' ', strtolower($artist)));
        
        // Prüfe auf Übereinstimmung ohne Berücksichtigung von Leerzeichen
        if (strpos(str_replace(' ', '', $normalizedArtist), str_replace(' ', '', $normalizedSearch)) !== false) {
            $suggestions[] = $artist; // Gib den originalen Künstlernamen zurück
        }
    }
}

// Sortiere nach Relevanz (beginnend mit dem Suchbegriff)
usort($suggestions, function($a, $b) use ($normalizedSearch) {
    $aPos = stripos($a, $normalizedSearch);
    $bPos = stripos($b, $normalizedSearch);
    return $aPos - $bPos;
});

// Begrenze auf 10 Ergebnisse
$suggestions = array_slice($suggestions, 0, 10);

echo json_encode($suggestions);
$conn->close();
?>
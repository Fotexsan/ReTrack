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

$stmt = $conn->prepare("SELECT DISTINCT master_metadata_album_album_name 
                       FROM songData 
                       WHERE accountId = ?");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $album = $row['master_metadata_album_album_name'];
    if ($album) {
        $normalizedAlbum = trim(preg_replace('/\s+/', ' ', strtolower($album)));
        if (strpos(str_replace(' ', '', $normalizedAlbum), str_replace(' ', '', $normalizedSearch)) !== false) {
            $suggestions[] = $album;
        }
    }
}

usort($suggestions, function($a, $b) use ($normalizedSearch) {
    $aPos = stripos($a, $normalizedSearch);
    $bPos = stripos($b, $normalizedSearch);
    return $aPos - $bPos;
});

$suggestions = array_slice($suggestions, 0, 10);

echo json_encode($suggestions);
$conn->close();
?>
<?php
require_once __DIR__ . '/../dbConnection.php';

header('Content-Type: application/json');

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $id = $_SESSION['id'];
} else {
    header("Location: login.php");
    die();
}

if (!isset($_GET['term'])) {
    echo json_encode([]);
    exit;
}

$term = $_GET['term'];

$conn = connect();
$stmt = $conn->prepare("SELECT DISTINCT master_metadata_album_artist_name FROM songData WHERE accountId = '$id' AND master_metadata_album_artist_name LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $term);
$stmt->execute();

$result = $stmt->get_result();
$suggestions = [];

while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['master_metadata_album_artist_name'];
}

echo json_encode($suggestions);
$conn->close();
?>
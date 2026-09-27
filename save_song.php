<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Replace with your actual database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'love_anniversary';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['title']) && isset($input['artist']) && isset($input['spotify_url'])) {
    $title = $conn->real_escape_string($input['title']);
    $artist = $conn->real_escape_string($input['artist']);
    $note = $conn->real_escape_string($input['note'] ?? '');
    $spotify_url = $conn->real_escape_string($input['spotify_url']);

    $stmt = $conn->prepare("INSERT INTO songs (title, artist, note, spotify_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $artist, $note, $spotify_url);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input data"]);
}

$conn->close();
?>
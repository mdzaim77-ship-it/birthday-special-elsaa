<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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

if (isset($input['type']) && isset($input['title']) && isset($input['media_url'])) {
    $type = $conn->real_escape_string($input['type']);
    $title = $conn->real_escape_string($input['title']);
    $desc = $conn->real_escape_string($input['description'] ?? '');
    $media_url = $conn->real_escape_string($input['media_url']);
    $date_label = $conn->real_escape_string($input['date_label'] ?? 'Custom Memory');

    $stmt = $conn->prepare("INSERT INTO media (type, title, description, media_url, date_label) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $type, $title, $desc, $media_url, $date_label);
    
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
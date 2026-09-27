<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Replace with your actual database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'love_anniversary';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["songs" => [], "media" => []]);
    exit();
}

// Fetch songs
$resultSongs = $conn->query("SELECT * FROM songs ORDER BY id DESC");
$songs = [];
if ($resultSongs) {
    while($row = $resultSongs.fetch_assoc()) {
        $songs[] = [
            "title" => $row['title'],
            "artist" => $row['artist'],
            "note" => $row['note'],
            "spotifyUrl" => $row['spotify_url']
        ];
    }
}

// Fetch media (photos/videos) if table exists
$media = [];
$resultMedia = $conn->query("SELECT * FROM media ORDER BY id DESC");
if ($resultMedia) {
    while($row = $resultMedia.fetch_assoc()) {
        $media[] = [
            "type" => $row['type'],
            "title" => $row['title'],
            "desc" => $row['description'],
            "src" => $row['media_url'],
            "date" => $row['date_label'] ?? 'Custom Memory'
        ];
    }
}

echo json_encode(["songs" => $songs, "media" => $media]);
$conn->close();
?>
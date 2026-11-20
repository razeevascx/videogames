<?php

require_once dirname(__DIR__) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$name = trim($_POST['GameName'] ?? '');
$description = trim($_POST['GameDescription'] ?? '');
$releasedDate = $_POST['DateReleased'] ?? '';
$rating = filter_input(INPUT_POST, 'GameRating', FILTER_VALIDATE_INT);
if (!$id || $name === '' || strlen($name) > 150 || strlen($description) > 5000 ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $releasedDate) ||
    $rating === false || $rating === null || $rating < 0 || $rating > 10) {
    http_response_code(422);
    exit('Please provide valid game details.');
}
$statement = $mysqli->prepare('UPDATE videogames SET game_name = ?, game_description = ?, released_date = ?, rating = ? WHERE game_id = ?');
$statement->bind_param('sssii', $name, $description, $releasedDate, $rating, $id);
$statement->execute();
$_SESSION['flash_toast'] = ['message' => 'Game updated successfully.', 'type' => 'success'];
redirect("game-details.php?id={$id}");

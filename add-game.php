<?php

require 'bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('add-game-form.php');
}

verify_csrf();

$name = trim($_POST['GameName'] ?? '');
$description = trim($_POST['GameDescription'] ?? '');
$releasedDate = $_POST['DateReleased'] ?? '';
$rating = filter_input(INPUT_POST, 'GameRating', FILTER_VALIDATE_INT);

if ($name === '' || strlen($name) > 150 || strlen($description) > 5000 ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $releasedDate) ||
    $rating === false || $rating === null || $rating < 0 || $rating > 10) {
    http_response_code(422);
    exit('Please provide a valid name, description, release date and rating from 0 to 10.');
}

$statement = $mysqli->prepare(
    'INSERT INTO videogames (game_name, game_description, released_date, rating) VALUES (?, ?, ?, ?)'
);
$statement->bind_param('sssi', $name, $description, $releasedDate, $rating);
$statement->execute();

redirect('list-games.php');

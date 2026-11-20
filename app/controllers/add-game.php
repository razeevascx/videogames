<?php

require_once dirname(__DIR__) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('add-game-form.php');
}

verify_csrf();

$rawgId = filter_input(INPUT_POST, 'rawg_id', FILTER_VALIDATE_INT) ?: null;
if ($rawgId !== null) {
    require_once dirname(__DIR__) . '/support/rawg.php';
    if (!rawg_cached_game($rawgId)) {
        http_response_code(422);
        exit('Please select a game from the search results before saving.');
    }
}

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
    'INSERT INTO videogames (game_name, game_description, released_date, rating, rawg_id) VALUES (?, ?, ?, ?, ?)'
);
$statement->bind_param('sssii', $name, $description, $releasedDate, $rating, $rawgId);
try {
    $statement->execute();
} catch (mysqli_sql_exception $exception) {
    if ($exception->getCode() === 1062) {
        http_response_code(409);
        exit('This RAWG game is already in your catalogue.');
    }
    throw $exception;
}

redirect('game-details.php?id=' . $mysqli->insert_id);

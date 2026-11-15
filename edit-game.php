<?php

require 'bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(404);
    exit('Game not found.');
}

$statement = $mysqli->prepare('SELECT * FROM videogames WHERE game_id = ?');
$statement->bind_param('i', $id);
$statement->execute();
$game = $statement->get_result()->fetch_assoc();

if (!$game) {
    http_response_code(404);
    exit('Game not found.');
}

render_template('edit-game.html', [
    'game' => $game,
    'csrf_token' => csrf_token(),
]);

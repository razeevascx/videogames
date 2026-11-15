<?php

require 'bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list-games.php');
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(422);
    exit('Invalid game.');
}
$statement = $mysqli->prepare('DELETE FROM videogames WHERE game_id = ?');
$statement->bind_param('i', $id);
$statement->execute();
redirect('list-games.php');

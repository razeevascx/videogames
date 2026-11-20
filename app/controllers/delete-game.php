<?php

require_once dirname(__DIR__) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
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
$_SESSION['flash_toast'] = ['message' => $statement->affected_rows ? 'Game deleted successfully.' : 'Game was already removed.', 'type' => $statement->affected_rows ? 'success' : 'warning'];
redirect('index.php');

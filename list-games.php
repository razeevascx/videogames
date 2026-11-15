<?php

require 'bootstrap.php';

$name = trim($_GET['name'] ?? '');
$fromYear = filter_input(INPUT_GET, 'from_year', FILTER_VALIDATE_INT);
$toYear = filter_input(INPUT_GET, 'to_year', FILTER_VALIDATE_INT);
$minRating = filter_input(INPUT_GET, 'min_rating', FILTER_VALIDATE_INT);

$conditions = [];
$params = [];
$types = '';
if ($name !== '') {
    $conditions[] = 'game_name LIKE ?';
    $params[] = "%{$name}%";
    $types .= 's';
}
if ($fromYear !== false && $fromYear !== null) {
    $conditions[] = 'released_date >= ?';
    $params[] = "{$fromYear}-01-01";
    $types .= 's';
}
if ($toYear !== false && $toYear !== null) {
    $conditions[] = 'released_date <= ?';
    $params[] = "{$toYear}-12-31";
    $types .= 's';
}
if ($minRating !== false && $minRating !== null) {
    $conditions[] = 'rating >= ?';
    $params[] = $minRating;
    $types .= 'i';
}

$sql = 'SELECT game_id, game_name, game_description, released_date, rating FROM videogames';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY released_date DESC, game_name';
$statement = $mysqli->prepare($sql);
if ($params) {
    $statement->bind_param($types, ...$params);
}
$statement->execute();
$games = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

render_template('list-games.html', [
    'name' => $name,
    'from_year' => $fromYear ?: '',
    'to_year' => $toYear ?: '',
    'min_rating' => $minRating ?: '',
    'games' => $games,
]);

<?php

require 'bootstrap.php';

$name = trim($_GET['name'] ?? '');
$fromYear = filter_input(INPUT_GET, 'from_year', FILTER_VALIDATE_INT);
$toYear = filter_input(INPUT_GET, 'to_year', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_GET, 'min_rating', FILTER_VALIDATE_INT);

$conditions = [];
$values = [];
$types = '';
if ($name !== '') {
    $conditions[] = 'game_name LIKE ?';
    $values[] = "%{$name}%";
    $types .= 's';
}
if ($fromYear !== false && $fromYear !== null) {
    $conditions[] = 'released_date >= ?';
    $values[] = "{$fromYear}-01-01";
    $types .= 's';
}
if ($toYear !== false && $toYear !== null) {
    $conditions[] = 'released_date <= ?';
    $values[] = "{$toYear}-12-31";
    $types .= 's';
}
if ($rating !== false && $rating !== null) {
    $conditions[] = 'rating >= ?';
    $values[] = $rating;
    $types .= 'i';
}

$sql = 'SELECT game_id, game_name, game_description, released_date, rating FROM videogames';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY released_date DESC, game_name';

$statement = $mysqli->prepare($sql);
if ($values) {
    $statement->bind_param($types, ...$values);
}
$statement->execute();
$games = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

render_template('search-games.html', [
    'name' => $name,
    'from_year' => $fromYear ?: '',
    'to_year' => $toYear ?: '',
    'min_rating' => $rating ?: '',
    'games' => $games,
]);

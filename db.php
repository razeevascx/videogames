<?php


try {
    $mysqli = new mysqli(
       'localhost',
       '2463090',
        '2463090',
         'db2463090'
    );
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Database connection failed.');
}

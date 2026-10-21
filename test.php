<?php
// Test file: checks the database connection works

require_once __DIR__ . '/db.php';

try {
    $pdo = getDatabaseConnection();
    $foodCount = $pdo->query('SELECT COUNT(*) FROM food')->fetchColumn();
    echo 'Connected. Foods in database: ' . $foodCount;
    echo '<br>Today is: ' . date('Y-m-d');
} catch (PDOException $e) {
    echo 'Could not connect: ' . $e->getMessage();
}

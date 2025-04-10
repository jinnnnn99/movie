<?php
$host = 'localhost';
$db = 'kang';
$user = 'kang';
$pass = 'L3cLCoVx';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sqlAll = "SELECT name, latitude, longitude, address, website FROM cinemas";
    $stmtAll = $pdo->query($sqlAll);
    $allCinemas = $stmtAll->fetchAll();

    echo json_encode(['cinemas' => $allCinemas]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

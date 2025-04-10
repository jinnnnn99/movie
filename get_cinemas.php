<?php
$host = 'localhost';
$db = 'eiga';
$user = 's2322019';
$pass = 't9xpMxuF';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sql = "SELECT name, latitude, longitude, address, website FROM cinemas";
    $stmt = $pdo->query($sql);
    $cinemas = $stmt->fetchAll();

    echo json_encode($cinemas);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

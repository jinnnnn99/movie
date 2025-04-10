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

    $userLat = $_GET['lat'];
    $userLng = $_GET['lng'];

    $sql = "SELECT name, latitude, longitude, address, website,
            ( 3959 * acos( cos( radians(:lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( latitude ) ) ) ) AS distance
            FROM cinemas
            ORDER BY distance
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['lat' => $userLat, 'lng' => $userLng]);
    $closestCinema = $stmt->fetch();

    $sqlAll = "SELECT name, latitude, longitude, address, website FROM cinemas";
    $stmtAll = $pdo->query($sqlAll);
    $allCinemas = $stmtAll->fetchAll();

    echo json_encode([
        'closest' => $closestCinema,
        'cinemas' => $allCinemas
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

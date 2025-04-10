<?php
function getDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000; // meters

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    $distance = $earthRadius * $c;

    return $distance;
}

$userLat = $_GET['latitude'];
$userLon = $_GET['longitude'];

$sql = 'SELECT * FROM cinemas';
$stmt = $pdo->query($sql);
$cinemas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$closestCinema = null;
$shortestDistance = PHP_INT_MAX;

foreach ($cinemas as $cinema) {
    $distance = getDistance($userLat, $userLon, $cinema['latitude'], $cinema['longitude']);
    if ($distance < $shortestDistance) {
        $shortestDistance = $distance;
        $closestCinema = $cinema;
    }
}

echo "The closest cinema is " . $closestCinema['name'] . " at a distance of " . $shortestDistance . " meters.";
?>

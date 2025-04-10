<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <title>時空間データベース</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        .navbar-nav .nav-item {
            margin-left: auto;
        }
        .logout-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                時空間データベース 2322019 姜顯珍
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="logout-btn" href="logout.php">ログアウト</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>大学の近くの映画館を表示します。</p>
                <p class="text-center">
                    <p>映画館の名前をクリックしてください</p>
                    <p>映画館の住所とウェブサイトが表示されます。</p>
                </p>
            </div>
        </div>
        <div class="row">   
            <div class="col-lg-6">
                <h2 class="text-center">映画館リスト</h2>
                <ul id="cinema-list"></ul>
            </div>
            <div class="col-lg-6">
                <h2 class="text-center">地図</h2>
                <div id="map"></div>
                <p id="status" class="text-center"></p>
                <div id="cinema-info" class="text-center">
                    <p id="cinema-name"></p>
                    <p id="cinema-address"></p>
                    <p><a id="cinema-website" href="#" target="_blank">ウェブサイト</a></p>
                </div>
            </div>
        </div>
    </div>
        
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCspsxGxDMADk_vr5B0Mf48SRM5Z0rH--4&libraries=places"></script>
    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 35.6313594162254, lng:  139.7867146384804 },
                zoom: 12
            });

            fetch('closest_cinema.php?lat=35.6762&lng=139.6503') 
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const cinemaList = document.getElementById('cinema-list');
                    data.cinemas.forEach(cinema => {
                        const li = document.createElement('li');
                        li.textContent = cinema.name;
                        li.style.cursor = 'pointer';
                        li.addEventListener('click', () => {
                            const mapSrc = `https://www.google.com/maps/embed/v1/place?key=AIzaSyCspsxGxDMADk_vr5B0Mf48SRM5Z0rH--4&q=${encodeURIComponent(cinema.name)}&center=${cinema.latitude},${cinema.longitude}&zoom=15`;
                            document.getElementById('map').innerHTML = `<iframe src="${mapSrc}" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen></iframe>`;

                            document.getElementById('cinema-name').textContent = cinema.name;
                            document.getElementById('cinema-address').textContent = cinema.address;
                            const websiteLink = document.getElementById('cinema-website');
                            websiteLink.href = cinema.website;
                            websiteLink.textContent = cinema.website;
                        });
                        cinemaList.appendChild(li);
                    });

                    if (data.closest) {
                        const closest = data.closest;
                        document.getElementById('cinema-name').textContent = closest.name;
                        document.getElementById('cinema-address').textContent = closest.address;
                        const websiteLink = document.getElementById('cinema-website');
                        websiteLink.href = closest.website;
                        websiteLink.textContent = closest.website;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('status').textContent = 'Error: ' + error.message;
                });
        }

        document.addEventListener('DOMContentLoaded', initMap);
    </script>
</body>
</html>

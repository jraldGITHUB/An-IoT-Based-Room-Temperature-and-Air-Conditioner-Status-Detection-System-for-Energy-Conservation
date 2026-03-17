<?php
session_start();

if(!isset($_SESSION['user'])){
header("Location: login.html");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>IoT Room Monitor | Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

</head>

<body>

<nav class="navbar navbar-expand-lg bg-light shadow-sm py-3">
<div class="container">

<a class="navbar-brand fw-bold fs-4" href="Web.php">
IoT Room Monitor
</a>

<div class="collapse navbar-collapse">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link active fw-semibold" href="Web.html">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="logs.html">Logs</a>
</li>

<a class="btn btn-danger ms-3" href="logout.php">Logout</a>

</ul>

</div>
</div>
</nav>

<div class="container mt-3">



<div class="alert alert-secondary text-center shadow-sm">
Last Update : <span id="lastUpdate">--</span>
</div>

</div>


<section class="container-fluid px-4 my-4">



<div class="row g-4">

<div class="col-lg-8">

<div class="card shadow-sm">

<div class="card-header bg-success text-white">
Live Room Map
</div>

<div class="card-body p-0">

<div id="map" style="height:510px;"></div>

<div class="p-2 small text-center">
<span style="color:blue;font-weight:bold;">■</span> Cold
<span style="color:gold;font-weight:bold;">■</span> Normal
<span style="color:red;font-weight:bold;">■</span> Hot
</div>

<div class="text-center mt-4 mb-3">
<p class="mb-1 fw-semibold">Aircon Runtime</p>
<h5 id="acRuntime" class="text-primary">0 min</h5>
</div>

</div>
</div>
</div>


<div class="col-lg-4">

<div class="card shadow-sm mb-3">

<div class="card-header">
Room Information
</div>

<div class="card-body text-center">

<h4 id="roomName">Lab 1</h4>

<p class="mb-2">Current Temperature</p>
<h2 class="fw-bold text-danger" id="temp">-- °C</h2>

<p class="mt-3 mb-1">Average Temperature</p>
<h4 id="avgTemp">-- °C</h4>

<p class="mt-3 mb-1">Minimum Temperature</p>
<h5 id="minTemp">-- °C</h5>

<p class="mt-2 mb-1">Maximum Temperature</p>
<h5 id="maxTemp">-- °C</h5>

</div>
</div>


<div class="card shadow-sm text-center mb-3">
<div class="card-body">

<h6 class="text-muted">Air Conditioner</h6>
<h3 class="fw-bold" id="acStatus">--</h3>

</div>
</div>


<div class="card shadow-sm text-center mb-3">
<div class="card-body">

<h6 class="text-muted">Exhaust Fan</h6>
<h3 class="fw-bold" id="fanStatus">--</h3>

</div>
</div>

<button class="btn btn-success w-100 py-2" id="refreshBtn">
Refresh Sensor Data
</button>

</div>
</div>


<div class="row mt-4">

<div class="col-12">

<div class="card shadow-sm">

<div class="card-header">
Temperature History
</div>

<div class="card-body">
<canvas id="tempChart"></canvas>
</div>

</div>

</div>

</div>

</section>


<script src="dashboard.js"></script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
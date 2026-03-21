<?php
include "../../../db.php";
session_start();

// PROTECT PAGE
if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}

// =========================
// LOG FUNCTION
// =========================
function logActivity($conn, $user_id, $action, $details = null){
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
}

// GET USER ID
$user_id = $_SESSION['user_id'] ?? null;


// =========================
// ADD ROOM
// =========================
if(isset($_POST['add_room'])){

    $name = $_POST['room_name'];
    $lat  = $_POST['latitude'];
    $lng  = $_POST['longitude'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_name, latitude, longitude) VALUES (?,?,?)");
    $stmt->bind_param("sdd", $name, $lat, $lng);
    $stmt->execute();

    $room_id = $stmt->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO room_sensors (room_id, sensor_status) VALUES (?, 'ON')");
    $stmt2->bind_param("i", $room_id);
    $stmt2->execute();

    // LOG ACTIVITY
    if($user_id){
        logActivity($conn, $user_id, "Added Room", "Room: $name");
    }

    header("Location: index.php?success=1");
    exit();
}


// =========================
// TOGGLE SENSOR
// =========================
if(isset($_GET['toggle'])){
    $id = $_GET['toggle'];

    $conn->query("
        UPDATE room_sensors
        SET sensor_status = IF(sensor_status='ON','OFF','ON')
        WHERE room_id = $id
    ");

    // GET UPDATED STATUS
    $res = $conn->query("SELECT sensor_status FROM room_sensors WHERE room_id = $id");
    $row = $res->fetch_assoc();
    $status = $row['sensor_status'];

    // LOG ACTIVITY
    if($user_id){
        logActivity($conn, $user_id, "Toggle Sensor", "Room ID: $id → $status");
    }

    header("Location: index.php");
    exit();
}


// =========================
// FETCH ROOMS
// =========================
$result = $conn->query("
    SELECT rooms.*, room_sensors.sensor_status
    FROM rooms
    LEFT JOIN room_sensors ON rooms.id = room_sensors.room_id
");
?>

<!DOCTYPE html>
<html>
<head>

<title>IoT Room Monitor</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="stylish.css">

<style>
#map{
height:420px;
border-radius:10px;
border:1px solid #334155;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-light shadow-sm py-3">
<div class="container">

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
<span class="navbar-toggler-icon"></span>
</button>

<a class="navbar-brand fw-bold fs-4" href="../index.php">
IoT Room Monitor
</a>

<div class="collapse navbar-collapse">
<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="../index.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../logs_page/index.php">Logs</a>
</li>

<li class="nav-item">
<a class="nav-link active fw-semibold" href="#">Manage Rooms</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../activity_logs/index.php">Activity logs</a>
</li>

<li class="nav-item">
<a class="nav-link " href="../account_settings/index.php">Account Settings</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../../../login/logout.php">Logout</a>
</li>

</ul>
</div>
</div>
</nav>


<div class="container-fluid mt-4">

<div class="row g-4">

<!-- ADD ROOM -->
<div class="col-lg-6">
<div class="form-card">

<h5 class="mb-4">Add Room</h5>

<?php if(isset($_GET['success'])){ ?>
<div class="alert alert-success">
Room added successfully
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Room Name</label>
<input type="text" name="room_name" class="form-control" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label>Latitude</label>
<input type="text" name="latitude" id="latitude" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Longitude</label>
<input type="text" name="longitude" id="longitude" class="form-control" required>
</div>
</div>

<button class="btn btn-success w-100 mb-3" name="add_room">
Add Room
</button>

</form>
</div>
</div>


<!-- SENSOR CONTROL -->
<div class="col-lg-6">
<div class="form-card">

<h5 class="mb-4">Room Sensor Control</h5>

<div class="table-responsive">
<table class="table table-dark table-hover">

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>

<td>
📍 <strong><?= $row['room_name'] ?></strong>  
- Sensor

<span class="badge <?= $row['sensor_status']=='ON' ? 'bg-success':'bg-secondary' ?>">
<?= $row['sensor_status'] ?>
</span>
</td>

<td width="120">
<a href="?toggle=<?= $row['id'] ?>" class="btn btn-warning btn-sm w-100">
Toggle
</a>
</td>

</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

</div>


<!-- MAP -->
<div class="row mt-4">
<div class="col-lg-12">
<div class="form-card">

<h5 class="mb-3">Map</h5>
<div id="map"></div>

</div>
</div>
</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map = L.map('map').setView([8.359634,124.869002],30);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
.addTo(map);

let marker;

map.on('click', function(e){

let lat = e.latlng.lat;
let lng = e.latlng.lng;

document.getElementById("latitude").value = lat;
document.getElementById("longitude").value = lng;

if(marker){
marker.setLatLng(e.latlng);
}else{
marker = L.marker(e.latlng).addTo(map);
}

});
</script>

</body>
</html>
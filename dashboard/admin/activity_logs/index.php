
<?php
session_start();
include "../../../db.php";

$user_id = $_SESSION['user_id'];

// DASHBOARD DATA
$totalRooms = $conn->query("SELECT COUNT(*) as total FROM rooms")->fetch_assoc()['total'];
$activeSensors = $conn->query("SELECT COUNT(*) as total FROM room_sensors WHERE sensor_status='ON'")->fetch_assoc()['total'];
$avgTemp = $conn->query("SELECT AVG(room_temp) as avg FROM sensor_logs")->fetch_assoc()['avg'];

// ACTIVITY LOGS
$logs = $conn->query("SELECT activity_logs.*, users.username 
FROM activity_logs
JOIN users ON users.id = activity_logs.user_id
ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* GLOBAL */
body{
    background-color:#0f172a;
    color:#e2e8f0;
    font-family:'Segoe UI',sans-serif;
}

/* NAVBAR */
.navbar{
    background-color:#020617 !important;
}

.navbar-brand{
    color:#38bdf8 !important;
}

.navbar .nav-link{
    color:#94a3b8 !important;
}

.navbar .nav-link:hover,
.navbar .nav-link.active{
    color:#ffffff !important;
}

/* MAIN CONTENT MARGIN */
.main-content{
    margin-top:40px;
    margin-bottom:40px;
    max-width:1200px;
}

/* CARDS */
.card{
    background:#1e293b;
    border:none;
    border-radius:14px;
    color:#e2e8f0;
    box-shadow:0 4px 15px rgba(0,0,0,0.4);
}

.card h6{
    color:#94a3b8;
    font-size:14px;
}

.card h3{
    font-weight:bold;
}

/* TABLE */
.table{
    color:#e2e8f0;
}

.table thead{
    background-color:#334155;
}

.table-dark{
    background-color:#3b467b;
}

.table-hover tbody tr:hover{
    background-color:#1e293b;
}

/* SCROLLBAR */
::-webkit-scrollbar{
    width:8px;
}

::-webkit-scrollbar-thumb{
    background:#334155;
    border-radius:10px;
}

/* MOBILE */
@media (max-width:768px){

.main-content{
    margin:20px;
}

.card{
    padding:15px !important;
}

h2{
    font-size:20px;
}

}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm py-3">
<div class="container">

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
<span class="navbar-toggler-icon"></span>
</button>

<a class="navbar-brand fw-bold fs-4" href="index.php">
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
<a class="nav-link" href="../add_monitor_rooms/index.php">Manage Rooms</a>
</li>

<li class="nav-item">
<a class="nav-link active fw-semibold" href="../activity_logs/index.php">Activity Logs</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../account_settings/index.php">Account Settings</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../../../login/logout.php">Logout</a>
</li>

</ul>

</div>
</div>
</nav>


<!-- MAIN CONTENT -->
<div class="container main-content">

<h2 class="mb-4">Activity Logs</h2>

<!-- KPI CARDS -->
<div class="row g-4">

<div class="col-md-4">
<div class="card p-3">
<h6>Total Rooms</h6>
<h3><?php echo $totalRooms; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h6>Active Sensors</h6>
<h3><?php echo $activeSensors; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h6>Average Temperature</h6>
<h3><?php echo number_format($avgTemp,2); ?>°C</h3>
</div>
</div>

</div>

<!-- ACTIVITY LOG TABLE -->
<div class="card mt-4 p-3">

<h5 class="mb-3">Recent Activity</h5>

<table class="table table-dark table-hover">

<thead>
<tr>
<th>User</th>
<th>Action</th>
<th>Details</th>
<th>Date</th>
</tr>
</thead>

<tbody>

<?php while($row = $logs->fetch_assoc()): ?>

<tr>
<td><?php echo $row['username']; ?></td>
<td><?php echo $row['action']; ?></td>
<td><?php echo $row['details']; ?></td>
<td><?php echo $row['created_at']; ?></td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```

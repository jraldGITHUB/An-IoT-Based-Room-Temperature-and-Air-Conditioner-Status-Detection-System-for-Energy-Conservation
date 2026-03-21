<?php
session_start();
include "../../db.php";

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s",$username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

$user_id = $user['id'];

$sql = "
SELECT 
rooms.id,
rooms.room_name,
rooms.latitude,
rooms.longitude,
rooms.id as device_id,
room_sensors.sensor_status
FROM user_rooms ur
JOIN rooms ON ur.room_id = rooms.id
LEFT JOIN room_sensors ON rooms.id = room_sensors.room_id
WHERE ur.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$user_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

echo json_encode($data);
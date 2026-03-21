<?php
include "../../../db.php";
session_start();

if(!isset($_SESSION['user_id'])){
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if(!$id) exit();

// GET ROOM INFO FIRST (for logging)
$res = $conn->query("
    SELECT rooms.id, rooms.room_name, room_sensors.sensor_status
    FROM rooms
    JOIN room_sensors ON rooms.id = room_sensors.room_id
    WHERE rooms.id = $id
");

$room = $res->fetch_assoc();

if(!$room) exit();

// TOGGLE
$conn->query("
    UPDATE room_sensors
    SET sensor_status = IF(sensor_status='ON','OFF','ON')
    WHERE room_id = $id
");

// GET NEW STATUS
$res2 = $conn->query("
    SELECT sensor_status FROM room_sensors WHERE room_id = $id
");
$new = $res2->fetch_assoc()['sensor_status'];

// ✅ LOG ACTIVITY
$stmt = $conn->prepare("
    INSERT INTO activity_logs (user_id, action, details)
    VALUES (?, ?, ?)
");

$action = "Toggle Sensor (Map)";
$details = "Room: ".$room['room_name']." → ".$new;

$stmt->bind_param("iss", $user_id, $action, $details);
$stmt->execute();

// RETURN JSON (IMPORTANT FOR SYNC)
echo json_encode([
    "status" => $new
]);
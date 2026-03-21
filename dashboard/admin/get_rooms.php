<?php
include "../../db.php";

$result = $conn->query("
SELECT 
    rooms.id,
    rooms.room_name,
    rooms.latitude,
    rooms.longitude,
    rooms.id as device_id,  -- IMPORTANT FIX
    room_sensors.sensor_status
FROM rooms
LEFT JOIN room_sensors ON rooms.id = room_sensors.room_id
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
?>
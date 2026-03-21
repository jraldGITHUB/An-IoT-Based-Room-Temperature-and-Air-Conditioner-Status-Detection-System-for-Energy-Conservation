<?php
session_start();
include "../../../db.php";

$username = $_SESSION['username'];

// ✅ Get user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

$user_id = $user['id'];

// ✅ Optional room filter (dropdown)
$roomFilter = isset($_GET['room']) ? $_GET['room'] : "all";

// ✅ BASE SQL (JOIN user_rooms)
$sql = "
SELECT 
    DATE(sl.recorded_at) AS date,
    TIME(sl.recorded_at) AS time,
    r.room_name AS room,
    sl.room_temp AS roomTemp,
    sl.exhaust_temp AS exhaustTemp,
    sl.aircon_status AS aircon,
    sl.fan_status AS exhaustFan,
    sl.runtime
FROM sensor_logs sl
JOIN rooms r ON sl.room_id = r.id
JOIN user_rooms ur ON r.id = ur.room_id
WHERE ur.user_id = ?
";

// ✅ Apply dropdown filter
if($roomFilter !== "all"){
    $sql .= " AND r.room_name = ?";
}

$sql .= " ORDER BY sl.id DESC";

$stmt = $conn->prepare($sql);

// ✅ Bind parameters properly
if($roomFilter !== "all"){
    $stmt->bind_param("is", $user_id, $roomFilter);
} else {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$logs = [];

while($row = $result->fetch_assoc()){
    $logs[] = $row;
}

echo json_encode($logs);
?>
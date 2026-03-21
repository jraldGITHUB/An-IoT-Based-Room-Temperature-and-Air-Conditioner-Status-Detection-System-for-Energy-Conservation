<?php
include "../../../db.php";

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
ORDER BY sl.id DESC
";

$result = $conn->query($sql);

$logs = [];

while($row = $result->fetch_assoc()){
    $logs[] = $row;
}

echo json_encode($logs);
?>
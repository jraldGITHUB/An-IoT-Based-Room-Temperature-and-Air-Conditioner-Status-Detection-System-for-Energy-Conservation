<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$date = $data["date"];
$time = $data["time"];
$room = $data["room"];
$roomTemp = $data["roomTemp"];
$exhaustTemp = $data["exhaustTemp"];
$aircon = $data["aircon"];
$exhaustFan = $data["exhaustFan"];
$runtime = $data["runtime"];

$sql = "INSERT INTO room_logs
(date,time,room,roomTemp,exhaustTemp,aircon,exhaustFan,runtime)
VALUES
('$date','$time','$room','$roomTemp','$exhaustTemp','$aircon','$exhaustFan','$runtime')";

$conn->query($sql);

echo "saved";

?>
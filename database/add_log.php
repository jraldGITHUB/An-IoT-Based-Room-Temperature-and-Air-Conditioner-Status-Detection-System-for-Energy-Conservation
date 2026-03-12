<?php

include "db.php";

$roomTemp = $_POST['roomTemp'];
$exhaustTemp = $_POST['exhaustTemp'];
$aircon = $_POST['aircon'];
$fan = $_POST['fan'];

$sql = "INSERT INTO room_logs (roomTemp,exhaustTemp,aircon,exhaustFan)
VALUES ('$roomTemp','$exhaustTemp','$aircon','$fan')";

mysqli_query($conn,$sql);

echo "success";

?>
<?php
include "../../../db.php";

$conn->query("TRUNCATE TABLE sensor_logs");

echo "cleared";
?>
<?php

include "db.php";

$sql = "SELECT * FROM room_logs ORDER BY id DESC";

$result = $conn->query($sql);

$logs = [];

while($row = $result->fetch_assoc()){
$logs[] = $row;
}

echo json_encode($logs);

?>
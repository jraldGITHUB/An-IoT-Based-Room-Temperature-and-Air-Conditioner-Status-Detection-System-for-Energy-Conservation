<?php
include "../../../db.php";

$result = $conn->query("
SELECT activity_logs.*, users.username 
FROM activity_logs
JOIN users ON users.id = activity_logs.user_id
ORDER BY created_at DESC LIMIT 10
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
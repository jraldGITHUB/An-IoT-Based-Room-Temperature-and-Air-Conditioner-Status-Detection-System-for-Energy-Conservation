<?php
session_start();
include "../../../db.php";

$username = $_SESSION['username'];

// GET USER ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s",$username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

$user_id = $user['id'];


$sql = "
SELECT r.room_name
FROM user_rooms ur
JOIN rooms r ON ur.room_id = r.id
WHERE ur.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$user_id);
$stmt->execute();

$result = $stmt->get_result();

$rooms = [];

while($row = $result->fetch_assoc()){
    $rooms[] = $row['room_name'];
}

echo json_encode($rooms);
?>
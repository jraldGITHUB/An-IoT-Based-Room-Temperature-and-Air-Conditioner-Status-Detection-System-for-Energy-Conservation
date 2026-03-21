<?php
include "../../../db.php";
include "../../../send_email.php";

$data = json_decode(file_get_contents("php://input"), true);

$roomName = $data["room"];
$roomTemp = $data["roomTemp"];
$exhaustTemp = $data["exhaustTemp"];
$aircon = $data["aircon"];
$fan = $data["exhaustFan"];
$runtime = $data["runtime"];

// 🔍 GET room_id
$stmt = $conn->prepare("SELECT id FROM rooms WHERE room_name=?");
$stmt->bind_param("s", $roomName);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Room not found";
    exit;
}

$row = $result->fetch_assoc();
$room_id = $row['id'];


// 💾 INSERT INTO sensor_logs
$stmt2 = $conn->prepare("
INSERT INTO sensor_logs 
(room_id, room_temp, exhaust_temp, aircon_status, fan_status, runtime)
VALUES (?,?,?,?,?,?)
");

$stmt2->bind_param(
"iddsss",
$room_id,
$roomTemp,
$exhaustTemp,
$aircon,
$fan,
$runtime
);

$stmt2->execute();


// 📧 EMAIL ALERT
if(strpos($runtime,"hrs") !== false){

    $hours = floatval($runtime);

    if($hours >= 4){
        sendAlert($roomName, $runtime);
    }

}

echo "saved";
?>
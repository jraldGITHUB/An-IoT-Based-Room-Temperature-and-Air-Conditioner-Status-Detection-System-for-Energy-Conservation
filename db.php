<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "iot_room_monitor";

$conn = new mysqli($host,$user,$password,$dbname);

if($conn->connect_error){
die("Connection Failed: ".$conn->connect_error);
}

?>
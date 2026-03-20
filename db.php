<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "iot_room_monitor";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Database connection failed");
}

?>
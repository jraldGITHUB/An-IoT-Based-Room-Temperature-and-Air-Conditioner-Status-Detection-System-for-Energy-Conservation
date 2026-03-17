<?php

session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) == 1){

$_SESSION['user'] = $username;

header("Location: Web.php");

}else{

echo "Invalid login";

}

?>
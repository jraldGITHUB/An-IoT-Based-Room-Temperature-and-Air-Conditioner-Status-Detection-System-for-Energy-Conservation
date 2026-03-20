<?php

include "../db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$role = "user"; // default role

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if username exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s",$username);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){

header("Location: register.php?error=1");
exit();

}

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
$stmt->bind_param("sss",$username,$hashedPassword,$role);

if($stmt->execute()){

header("Location: register.php?success=1");

}else{

echo "Error: ".$conn->error;

}

?>
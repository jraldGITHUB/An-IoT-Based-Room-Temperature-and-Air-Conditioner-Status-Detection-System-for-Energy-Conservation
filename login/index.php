<?php
session_start();
include "../db.php";

$error = "";

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        if($row['role'] == 'admin')
        {
            header("Location: ../dashboard/admin/index.php");
        }
        elseif($row['role'] == 'manager')
        {
            header("Location: ../dashboard/manager/index.php");
        }
        else
        {
            header("Location: ../dashboard/user/index.php");
        }

        exit();
    }
    else
    {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | IoT Room Monitor</title>

<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<!-- LEFT SIDE -->
<div class="left-panel">

<h1>IoT Room<br>Monitoring System</h1>

<p>
Real-time monitoring of room temperature, humidity, and environmental conditions using IoT sensors.
</p>

<div class="feature">
<strong>Temperature Monitoring</strong><br>
Track real-time room temperature.
</div>

<div class="feature">
<strong>Humidity Monitoring</strong><br>
Monitor humidity levels using DHT11 sensor.
</div>

<div class="feature">
<strong>Real-Time Alerts</strong><br>
Get notifications when conditions exceed limits.
</div>

</div>

<!-- RIGHT SIDE -->
<div class="right-panel">

<div class="login-card">

<div class="logo">IoT Room Monitor</div>

<h3>Welcome Back</h3>
<p>Sign in to access your monitoring dashboard.</p>

<!-- ERROR MESSAGE -->
<?php if(!empty($error)){ ?>
<div class="alert alert-danger text-center">
<?php echo $error; ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="d-grid">
<button type="submit" name="login" class="btn-login">Sign In</button>
</div>

<div class="text-center mt-3">
<a href="../register/register.php">Create an account</a>
</div>

</form>

</div>

</div>

</body>
</html>
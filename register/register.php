<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | IoT Room Monitor</title>

<link rel="stylesheet" href="../login/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<!-- LEFT SIDE -->
<div class="left-panel">

<h1>IoT Room<br>Monitoring System</h1>

<p>
Create an account to start monitoring room temperature, humidity, and environmental conditions using IoT sensors.
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

<h3>Create Account</h3>
<p>Register to access the monitoring dashboard.</p>

<!-- ERROR MESSAGE -->
<?php if(isset($_GET['error'])): ?>
<div class="alert alert-danger text-center">
Username already exists
</div>
<?php endif; ?>

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success text-center">
Account created successfully
</div>

<script>
setTimeout(function(){
window.location.href="../login/index.php";
},1000);
</script>

<?php endif; ?>

<form action="register_process.php" method="POST">

<div class="mb-3">
<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="d-grid">
<button class="btn-login">Register</button>
</div>

</form>

<div class="text-center mt-3">
<a href="../login/index.php">Already have an account? Login</a>
</div>

</div>

</div>

</body>
</html>
<?php
session_start();
include "../../../db.php";

// PROTECT
if(!isset($_SESSION['username'])){
    header("Location: ../../../login/index.php");
    exit();
}

// ============================
// ADD USER (NOW USING MD5)
// ============================
if(isset($_POST['add_user'])){

    $username = trim($_POST['username']);
    $password = md5($_POST['password']); // ✅ CHANGED TO MD5
    $role     = $_POST['role'];

    // INSERT USER
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    // LOG ACTIVITY
    $admin_id = $_SESSION['user_id'];
    $action = "Create User";
    $details = "Created account: $username ($role)";

    $log = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $log->bind_param("iss", $admin_id, $action, $details);
    $log->execute();

    header("Location: index.php");
    exit();
}

// ============================
// DELETE USER
// ============================
if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    if($id != $_SESSION['user_id']){

        $res = $conn->query("SELECT username, role FROM users WHERE id=$id");
        $user = $res->fetch_assoc();

        $conn->query("DELETE FROM users WHERE id=$id");

        $admin_id = $_SESSION['user_id'];
        $action = "Delete User";
        $details = "Deleted account: {$user['username']} ({$user['role']})";

        $log = $conn->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        $log->bind_param("iss", $admin_id, $action, $details);
        $log->execute();
    }

    header("Location: index.php");
    exit();
}

// ============================
// FETCH USERS
// ============================
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Accounts</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background-color:#0f172a;
    color:#e2e8f0;
    font-family:'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar{
    background-color:#020617 !important;
}

.navbar-brand{
    color:#38bdf8 !important;
}

.navbar .nav-link{
    color:#94a3b8 !important;
}

.navbar .nav-link:hover,
.navbar .nav-link.active{
    color:#ffffff !important;
}

/* LAYOUT */
.main-wrapper{
    margin-top:30px;
}

/* CARDS */
.card{
    background:#1e293b;
    border:none;
    border-radius:14px;
    color:#e2e8f0;
    box-shadow:0 6px 18px rgba(0,0,0,0.5);
}

/* FORM */
.form-control,
.form-select{
    background-color:#020617;
    border:1px solid #334155;
    color:#e2e8f0;
    height:45px;
}

.form-control:focus,
.form-select:focus{
    border-color:#38bdf8;
    box-shadow:none;
}

/* BUTTON */
.btn-success{
    background:#22c55e;
    height:45px;
}

/* TABLE */
.table{
    color:#e2e8f0;
}

.table thead{
    background-color:#334155;
}

.table-hover tbody tr:hover{
    background-color:#1e293b;
}

/* ROLE BADGE */
.role-badge{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
}

.role-admin{ background:#ef4444; }
.role-manager{ background:#3b82f6; }
.role-user{ background:#64748b; }

/* ALIGN */
.text-end{
    text-align:right;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg shadow-sm py-3">
<div class="container">

<a class="navbar-brand fw-bold fs-4" href="../index.php">
IoT Room Monitor
</a>

<div class="collapse navbar-collapse">
<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="../index.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../logs_page/index.php">Logs</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../add_monitor_rooms/index.php">Manage Rooms</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../activity_logs/index.php">Activity logs</a>
</li>

<li class="nav-item">
<a class="nav-link active fw-semibold" href="../account_settings/index.php">Account Settings</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../../../login/logout.php">Logout</a>
</li>

</ul>
</div>
</div>
</nav>

<div class="container main-wrapper">

<h3 class="mb-4">Manage Accounts</h3>

<div class="row g-4">

<!-- CREATE -->
<div class="col-lg-4">
<div class="card p-4">

<h5 class="mb-3">Create Account</h5>

<form method="POST">

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="role" class="form-select mb-3" required>
<option value="">Select Role</option>
<option value="admin">Admin</option>
<option value="manager">Manager</option>
<option value="user">User</option>
</select>

<button type="submit" name="add_user" class="btn btn-success w-100">
Create Account
</button>

</form>

</div>
</div>

<!-- LIST -->
<div class="col-lg-8">
<div class="card p-4">

<h5 class="mb-3">All Accounts</h5>

<table class="table table-hover align-middle">
<thead>
<tr>
<th>ID</th>
<th>Username</th>
<th>Role</th>
<th class="text-end">Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $users->fetch_assoc()): ?>
<tr>

<td><?php echo $row['id']; ?></td>
<td class="fw-semibold"><?php echo $row['username']; ?></td>

<td>
<span class="role-badge role-<?php echo $row['role']; ?>">
<?php echo ucfirst($row['role']); ?>
</span>
</td>

<td class="text-end">

<?php if($row['id'] != $_SESSION['user_id']): ?>
<a href="?delete=<?php echo $row['id']; ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this account?')">
   Delete
</a>
<?php else: ?>
<span class="text-muted">Current User</span>
<?php endif; ?>

</td>

</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</div>

</div>

</div>

</body>
</html>
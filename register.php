<?php
session_start();
include 'config/db.php';
$msg = ""; $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; $email = $_POST['email']; $phone = $_POST['phone'];
    $cat = $_POST['category']; $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email' OR phone='$phone'");
    if($check->num_rows > 0) { $err = "Email/Phone already exists!"; } 
    else {
        $sql = "INSERT INTO users (name, email, phone, password, role, category, status) VALUES ('$name', '$email', '$phone', '$pass', 'provider', '$cat', 'pending')";
        if($conn->query($sql)) $msg = "Registration successful! Wait for approval.";
        else $err = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Register</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{background:#0f172a;height:100vh;display:flex;align-items:center;justify-content:center;}</style></head>
<body>
<div class="card p-4 border-0 text-white" style="width:100%; max-width:400px; background:rgba(30,41,59,0.7); backdrop-filter:blur(10px); border-radius:20px;">
    <h4 class="text-center fw-bold mb-3">Partner Registration</h4>
    <?php if($msg): ?><div class="alert alert-success py-2 small"><?= $msg ?></div><?php endif; ?>
    <?php if($err): ?><div class="alert alert-danger py-2 small"><?= $err ?></div><?php endif; ?>
    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
        <input type="text" name="phone" class="form-control mb-2" placeholder="Phone" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <select name="category" class="form-control mb-2 text-white bg-dark">
            <option value="AC Repair">AC Repair</option><option value="Plumbing">Plumbing</option><option value="Cleaning">Cleaning</option>
        </select>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button class="btn btn-primary w-100">Register</button>
    </form>
    <div class="text-center mt-3"><a href="login.php" class="text-muted small">Back to Login</a></div>
</div>
</body></html>
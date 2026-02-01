<?php
session_start();
include 'config/db.php';

if (isset($_SESSION['provider_id'])) { header("Location: dashboard.php"); exit(); }

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'provider'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Plain Text & Hash Support
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            if($row['status'] == 'banned') {
                $error = "Account suspended! Contact admin.";
            } else {
                $_SESSION['provider_id'] = $row['id'];
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = "Incorrect Password!";
        }
    } else {
        $error = "Provider account not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Provider Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#0f172a;height:100vh;display:flex;align-items:center;justify-content:center;color:white;}</style>
</head>
<body>
    <div class="card p-4 border-0" style="width:100%; max-width:400px; background:rgba(30,41,59,0.7); backdrop-filter:blur(10px); border-radius:20px;">
        <h3 class="text-center fw-bold mb-4">Wilko<span class="text-primary">Pro</span></h3>
        <?php if($error): ?><div class="alert alert-danger py-2 small text-center"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3"><a href="register.php" class="text-muted small text-decoration-none">Join as Partner</a></div>
    </div>
</body>
</html>
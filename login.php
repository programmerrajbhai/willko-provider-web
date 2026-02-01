<?php
session_start();
include 'config/db.php';
if (isset($_SESSION['provider_id'])) { header("Location: dashboard.php"); exit(); }
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'provider'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            $_SESSION['provider_id'] = $row['id'];
            $_SESSION['provider_name'] = $row['name'];
            header("Location: dashboard.php"); exit();
        } else { $error = "Incorrect Password!"; }
    } else { $error = "Account not found!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Provider Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.2) 0px, transparent 50%);
            height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%; max-width: 420px; padding: 40px;
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: rgba(2, 6, 23, 0.5); border: 1px solid rgba(255,255,255,0.1);
            color: #fff; padding: 14px; border-radius: 12px;
        }
        .form-control:focus { background: rgba(2, 6, 23, 0.8); border-color: #6366f1; color: #fff; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5); border: 0;
            padding: 14px; width: 100%; border-radius: 12px; font-weight: 700;
            transition: 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white mb-1">Wilko<span style="color:#6366f1">Pro</span></h2>
            <p class="text-white-50 small">Access your partner dashboard</p>
        </div>

        <?php if($error): ?><div class="alert alert-danger bg-danger bg-opacity-25 border-0 text-danger text-center py-2 mb-4 rounded-3 small"><?= $error ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn btn-primary">Sign In</button>
        </form>
        
        <div class="text-center mt-4 pt-3 border-top border-secondary border-opacity-25">
            <p class="text-white-50 small mb-0">Don't have an account? <a href="register.php" class="text-white fw-bold text-decoration-none">Apply Now</a></p>
        </div>
    </div>
</body>
</html>
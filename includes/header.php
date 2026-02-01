<?php
session_start();
// লগইন চেক (Login & Register পেজ ছাড়া বাকি সব পেজে সেশন চেক করবে)
$public_pages = ['login.php', 'register.php', 'index.php'];
if (!isset($_SESSION['provider_id']) && !in_array(basename($_SERVER['PHP_SELF']), $public_pages)) {
    header("Location: login.php");
    exit();
}
include __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wilko Provider</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0f172a;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #38bdf8;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body { 
            font-family: 'Manrope', sans-serif; 
            background-color: var(--bg-dark); 
            color: var(--text-main);
            overflow-x: hidden; 
            padding-bottom: 80px; /* For Mobile Bottom Nav if needed */
        }

        /* Sidebar Styling (Desktop) */
        .sidebar { width: 260px; height: 100vh; position: fixed; top: 0; left: 0; background: #020617; border-right: 1px solid var(--glass-border); z-index: 1000; transition: 0.3s; }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }

        /* Mobile Sidebar Handling */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex !important; }
        }

        /* Glass Cards */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Inputs */
        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            padding: 12px; border-radius: 10px;
        }
        .form-control:focus { border-color: var(--primary) !important; box-shadow: 0 0 10px rgba(56, 189, 248, 0.2); }

        /* Buttons */
        .btn-primary { background: linear-gradient(135deg, #0ea5e9, #2563eb); border: none; color: white; font-weight: 700; padding: 10px 20px; border-radius: 10px; }
        .btn-primary:hover { box-shadow: 0 0 15px rgba(14, 165, 233, 0.4); transform: translateY(-2px); }
        .btn-outline-light { border-color: var(--glass-border); color: var(--text-muted); }
        .btn-outline-light:hover { background: rgba(255,255,255,0.05); color: white; }

        /* Mobile Header (Hidden on Desktop) */
        .mobile-header {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: rgba(2, 6, 23, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky; top: 0; z-index: 999;
        }
    </style>
</head>
<body>

<div class="mobile-header">
    <h4 class="fw-bold m-0 text-white">Wilko<span class="text-primary">Pro</span></h4>
    <button class="btn btn-outline-light border-0" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars fa-lg"></i>
    </button>
</div>
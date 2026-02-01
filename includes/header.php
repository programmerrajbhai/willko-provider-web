<?php
session_start();
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
    <title>Wilko Provider Pro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #6366f1; /* Indigo Neon */
            --secondary: #ec4899; /* Pink Neon */
            --dark-bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.6);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-white: #f1f5f9;
            --text-gray: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            /* Advanced Mesh Gradient Background */
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-white);
            overflow-x: hidden;
        }

        /* --- Glassmorphism Card --- */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.1);
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 270px;
            height: 95vh;
            position: fixed;
            top: 2.5vh;
            left: 20px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            padding: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        
        .main-content {
            margin-left: 310px;
            padding: 30px 30px 30px 0;
            min-height: 100vh;
        }

        /* --- Nav Links --- */
        .nav-link {
            color: var(--text-gray);
            padding: 14px 18px;
            border-radius: 14px;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex; align-items: center; gap: 12px;
            transition: all 0.2s ease;
            margin-bottom: 8px;
        }
        .nav-link i { width: 22px; text-align: center; transition: 0.2s; }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15), transparent);
            color: #fff;
            border-left: 3px solid var(--primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .nav-link.active i { color: var(--primary); transform: scale(1.1); }

        /* --- Forms & Buttons --- */
        .form-control, .form-select {
            background: rgba(2, 6, 23, 0.5) !important;
            border: 1px solid var(--border-color) !important;
            color: #fff !important;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        /* --- Mobile Responsive --- */
        .mobile-header { display: none; }
        
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-120%); height: 100vh; top: 0; left: 0; border-radius: 0; width: 280px; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header {
                display: flex; justify-content: space-between; align-items: center;
                padding: 15px 20px;
                background: rgba(15, 23, 42, 0.9);
                backdrop-filter: blur(15px);
                position: sticky; top: 0; z-index: 999;
                border-bottom: 1px solid var(--border-color);
            }
        }
    </style>
</head>
<body>

<div class="mobile-header">
    <h4 class="fw-bold m-0 text-white" style="font-family: 'Inter', sans-serif;">Wilko<span style="color: var(--primary);">Pro</span></h4>
    <button class="btn text-white border-0" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars fa-lg"></i>
    </button>
</div>
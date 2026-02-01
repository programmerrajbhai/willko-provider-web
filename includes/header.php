<?php
session_start();
// Public pages that don't require login
$public_pages = ['login.php', 'register.php', 'index.php'];

if (!isset($_SESSION['provider_id']) && !in_array(basename($_SERVER['PHP_SELF']), $public_pages)) {
    header("Location: login.php");
    exit();
}
include __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wilko Provider Pro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #ec4899;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #1e293b; /* Solid Dark Color Fallback */
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0; 
            background: #020617; border-right: 1px solid var(--border-color); 
            z-index: 1000; transition: 0.3s; 
        }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex !important; }
        }

        /* Glass / Dark Cards */
        .glass-card {
            background: rgba(30, 41, 59, 0.95); /* More Opaque Dark */
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Inputs & Tables */
        .form-control, .form-select {
            background-color: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-control:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }

        .table { --bs-table-bg: transparent; --bs-table-color: var(--text-muted); border-color: var(--border-color); }
        .table th { color: var(--text-main); font-weight: 600; }
        .table td { color: var(--text-muted); }

        /* Buttons */
        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: #4f46e5; }
        
        .mobile-header {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: rgba(2, 6, 23, 0.95);
            border-bottom: 1px solid var(--border-color);
            position: sticky; top: 0; z-index: 999;
        }
    </style>
</head>
<body>

<div class="mobile-header">
    <h4 class="fw-bold m-0 text-white">Wilko<span class="text-primary">Pro</span></h4>
    <button class="btn btn-outline-secondary border-0" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars fa-lg text-white"></i>
    </button>
</div>
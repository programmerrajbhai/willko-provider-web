<div class="sidebar d-flex flex-column">
    <div class="d-flex align-items-center gap-3 px-4 py-4 mb-2">
        <div class="position-relative">
            <div class="bg-gradient-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-lg" 
                 style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #818cf8);">
                <i class="fas fa-bolt text-white fs-4"></i>
            </div>
            <div class="position-absolute top-50 start-50 translate-middle" 
                 style="width: 40px; height: 40px; background: #6366f1; filter: blur(20px); opacity: 0.6; z-index: -1;"></div>
        </div>
        <div>
            <h4 class="fw-bold m-0 text-white" style="font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">
                Wilko<span style="color: #818cf8;">Pro</span>
            </h4>
            <div class="d-flex align-items-center gap-1">
                <span class="badge bg-success rounded-circle p-1" style="width: 6px; height: 6px;"></span>
                <small class="text-muted" style="font-size: 10px; letter-spacing: 1px; text-transform: uppercase;">Partner Panel</small>
            </div>
        </div>
    </div>
    
    <ul class="nav flex-column gap-2 px-3" style="flex-grow: 1; overflow-y: auto;">
        
        <li class="nav-item">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block" style="font-size: 10px; letter-spacing: 1.5px;">Main Menu</small>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                <i class="fas fa-th-large"></i> 
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'new_jobs.php' ? 'active' : '' ?>" href="new_jobs.php">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-bell"></i> 
                        <span>New Requests</span>
                    </div>
                    <?php 
                        // Optional: Fetch real count if needed here or keep static/demo
                        // $new_count = $conn->query("SELECT COUNT(*) FROM bookings WHERE status='pending' AND provider_id IS NULL")->fetch_row()[0] ?? 0;
                    ?>
                    <span class="badge bg-danger rounded-pill shadow-sm" style="font-size: 10px;">2</span>
                </div>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'my_jobs.php' ? 'active' : '' ?>" href="my_jobs.php">
                <i class="fas fa-briefcase"></i> 
                <span>Active Jobs</span>
            </a>
        </li>

        <li class="nav-item mt-3">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block" style="font-size: 10px; letter-spacing: 1.5px;">Finance & Records</small>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : '' ?>" href="history.php">
                <i class="fas fa-history"></i> 
                <span>Job History</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'wallet.php' ? 'active' : '' ?>" href="wallet.php">
                <i class="fas fa-wallet"></i> 
                <span>My Wallet</span>
            </a>
        </li>

        <li class="nav-item mt-3">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block" style="font-size: 10px; letter-spacing: 1.5px;">Account</small>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>" href="profile.php">
                <i class="fas fa-user-cog"></i> 
                <span>Settings</span>
            </a>
        </li>
    </ul>

    <div class="mt-auto p-3">
        <div class="p-3 rounded-4 border border-secondary border-opacity-25 position-relative overflow-hidden" 
             style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px);">
            
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="position-relative">
                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow" 
                         style="width: 40px; height: 40px; background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                        <?= strtoupper(substr($_SESSION['provider_name'] ?? 'P', 0, 1)) ?>
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-success border border-dark rounded-circle p-1"></span>
                </div>
                <div style="line-height: 1.2; overflow: hidden;">
                    <h6 class="text-white fw-bold m-0 text-truncate"><?= explode(' ', $_SESSION['provider_name'] ?? 'Provider')[0] ?></h6>
                    <small class="text-muted" style="font-size: 11px;">Pro Member</small>
                </div>
            </div>

            <a href="logout.php" class="btn btn-danger w-100 btn-sm rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" 
               style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<div class="main-content">
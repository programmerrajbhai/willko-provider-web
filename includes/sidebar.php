<div class="sidebar">
    <div class="d-flex align-items-center gap-3 mb-5 px-2">
        <div class="d-flex align-items-center justify-content-center rounded-3 shadow-lg" 
             style="width: 45px; height: 45px; background: linear-gradient(135deg, #6366f1, #818cf8);">
            <i class="fas fa-bolt text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-bold m-0 text-white" style="letter-spacing: -0.5px;">Wilko<span style="color: #818cf8;">Services</span></h4>
            <small class="text-gray" style="font-size: 11px; letter-spacing: 1px;">PARTNER PANEL</small>
        </div>
    </div>
    
    <ul class="nav flex-column gap-1" style="flex-grow: 1;">
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                <i class="fas fa-grid-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'new_jobs.php' ? 'active' : '' ?>" href="new_jobs.php">
                <i class="fas fa-bell"></i> New Requests
                <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 10px;">2</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'my_jobs.php' ? 'active' : '' ?>" href="my_jobs.php">
                <i class="fas fa-briefcase"></i> My Jobs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : '' ?>" href="history.php">
                <i class="fas fa-clock-rotate-left"></i> History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'wallet.php' ? 'active' : '' ?>" href="wallet.php">
                <i class="fas fa-wallet"></i> Wallet
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>" href="profile.php">
                <i class="fas fa-user-gear"></i> Settings
            </a>
        </li>
    </ul>

    <div class="mt-auto pt-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(255,255,255,0.03);">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 35px; height: 35px;">
                    <?= strtoupper(substr($_SESSION['provider_name'] ?? 'P', 0, 1)) ?>
                </div>
                <div style="line-height: 1.2;">
                    <small class="d-block text-white fw-bold"><?= explode(' ', $_SESSION['provider_name'] ?? 'Provider')[0] ?></small>
                    <small class="text-muted" style="font-size: 10px;">Online</small>
                </div>
            </div>
            <a href="logout.php" class="text-danger p-2 hover-bg-dark rounded-circle" title="Logout">
                <i class="fas fa-power-off"></i>
            </a>
        </div>
    </div>
</div>

<div class="main-content">
<div class="sidebar d-flex flex-column">
    <div class="p-4 d-flex align-items-center gap-3 border-bottom border-secondary border-opacity-10">
        <div class="bg-primary rounded-3 p-2 text-white"><i class="fas fa-bolt"></i></div>
        <h4 class="fw-bold m-0 text-white">Wilko<span class="text-primary">Pro</span></h4>
    </div>
    
    <ul class="nav flex-column px-3 mt-4 gap-2">
        <li class="nav-item">
            <a class="nav-link text-muted d-flex align-items-center gap-3 p-3 rounded-3 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-primary text-white' : '' ?>" href="dashboard.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted d-flex align-items-center gap-3 p-3 rounded-3 <?= basename($_SERVER['PHP_SELF']) == 'new_jobs.php' ? 'bg-primary text-white' : '' ?>" href="new_jobs.php">
                <i class="fas fa-bell"></i> New Requests
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted d-flex align-items-center gap-3 p-3 rounded-3 <?= basename($_SERVER['PHP_SELF']) == 'my_jobs.php' ? 'bg-primary text-white' : '' ?>" href="my_jobs.php">
                <i class="fas fa-briefcase"></i> My Jobs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted d-flex align-items-center gap-3 p-3 rounded-3 <?= basename($_SERVER['PHP_SELF']) == 'wallet.php' ? 'bg-primary text-white' : '' ?>" href="wallet.php">
                <i class="fas fa-wallet"></i> Wallet
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted d-flex align-items-center gap-3 p-3 rounded-3 <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-primary text-white' : '' ?>" href="profile.php">
                <i class="fas fa-user"></i> Profile
            </a>
        </li>
    </ul>

    <div class="mt-auto p-4">
        <a href="logout.php" class="btn btn-danger w-100 fw-bold bg-opacity-10 text-danger border-0"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
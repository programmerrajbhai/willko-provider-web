<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();

// Toggle Online Status
if(isset($_GET['status_toggle'])) {
    $new = $p['is_online'] ? 0 : 1;
    $conn->query("UPDATE users SET is_online=$new WHERE id=$pid");
    echo "<script>location.href='dashboard.php'</script>";
}

// Stats
$pending = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status='assigned'")->fetch_row()[0];
$completed = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0];
$earnings = $conn->query("SELECT SUM(final_total) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0] ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-white mb-0">Hello, <?= explode(' ', $p['name'])[0] ?> 👋</h3>
        <p class="text-muted small">Here is your daily summary</p>
    </div>
    <a href="dashboard.php?status_toggle=1" class="btn <?= $p['is_online']?'btn-success':'btn-secondary' ?> rounded-pill px-4 fw-bold">
        <?= $p['is_online'] ? '<i class="fas fa-wifi me-2"></i> ONLINE' : '<i class="fas fa-power-off me-2"></i> OFFLINE' ?>
    </a>
</div>

<div class="row g-3">
    <div class="col-6 col-md-4">
        <div class="glass-card p-3">
            <small class="text-muted text-uppercase fw-bold">Earnings</small>
            <h3 class="fw-bold text-white mb-0">SAR <?= number_format($earnings) ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="glass-card p-3">
            <small class="text-muted text-uppercase fw-bold">Active Jobs</small>
            <h3 class="fw-bold text-warning mb-0"><?= $pending ?></h3>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="glass-card p-3 d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted text-uppercase fw-bold">Rating</small>
                <h3 class="fw-bold text-warning mb-0"><i class="fas fa-star"></i> <?= $p['rating'] ?></h3>
            </div>
            <div class="bg-dark p-3 rounded-circle"><i class="fas fa-trophy text-warning"></i></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
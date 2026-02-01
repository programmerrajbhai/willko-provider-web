<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();

// --- 1. TOGGLE ONLINE STATUS ---
if(isset($_GET['status_toggle'])) {
    $new = $p['is_online'] ? 0 : 1;
    $conn->query("UPDATE users SET is_online=$new WHERE id=$pid");
    echo "<script>location.href='dashboard.php'</script>";
}

// --- 2. FETCH ANALYTICS ---
// Total Earnings (Completed Jobs)
$earnings = $conn->query("SELECT SUM(final_total) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0] ?? 0;

// Active Jobs (Assigned, On Way, Started)
$active_jobs = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status IN ('assigned', 'on_way', 'started')")->fetch_row()[0];

// Total Completed Jobs
$completed_jobs = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0];

// Pending Requests
$new_requests = $conn->query("SELECT COUNT(*) FROM bookings WHERE status='pending' AND provider_id=$pid")->fetch_row()[0];

// --- 3. CHART DATA (LAST 6 MONTHS) ---
$months = [];
$income_data = [];
for ($i = 5; $i >= 0; $i--) {
    $month_start = date("Y-m-01", strtotime("-$i months"));
    $month_end = date("Y-m-t", strtotime("-$i months"));
    $month_name = date("M", strtotime("-$i months"));
    
    $sql = "SELECT SUM(final_total) FROM bookings WHERE provider_id=$pid AND status='completed' AND schedule_date BETWEEN '$month_start' AND '$month_end'";
    $income = $conn->query($sql)->fetch_row()[0] ?? 0;
    
    array_push($months, $month_name);
    array_push($income_data, $income);
}
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold text-white mb-1">Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, <span class="text-primary fw-bold"><?= explode(' ', $p['name'])[0] ?></span>! Here's your overview.</p>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <div class="glass-card px-4 py-2 rounded-pill d-flex align-items-center gap-3 border-0 bg-dark bg-opacity-50">
            <div>
                <small class="text-muted d-block text-uppercase" style="font-size: 10px;">Status</small>
                <span class="fw-bold <?= $p['is_online']?'text-success':'text-danger' ?>">
                    <?= $p['is_online'] ? '● ONLINE' : '○ OFFLINE' ?>
                </span>
            </div>
            <div class="vr bg-secondary opacity-25"></div>
            <a href="dashboard.php?status_toggle=1" class="btn btn-sm btn-<?= $p['is_online']?'danger':'success' ?> rounded-pill px-3 fw-bold">
                <?= $p['is_online'] ? 'Go Offline' : 'Go Online' ?>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden border-bottom border-4 border-success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted text-uppercase small fw-bold mb-1">Total Earnings</p>
                    <h3 class="text-white fw-bold mb-0">SAR <?= number_format($earnings) ?></h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2">
                    <i class="fas fa-arrow-up small me-1"></i> Lifetime
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden border-bottom border-4 border-primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted text-uppercase small fw-bold mb-1">Active Jobs</p>
                    <h3 class="text-white fw-bold mb-0"><?= $active_jobs ?></h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fas fa-briefcase fa-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="my_jobs.php" class="text-decoration-none text-primary small fw-bold">View Ongoing Tasks <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden border-bottom border-4 border-danger">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted text-uppercase small fw-bold mb-1">New Requests</p>
                    <h3 class="text-white fw-bold mb-0"><?= $new_requests ?></h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                    <i class="fas fa-bell fa-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="new_jobs.php" class="text-decoration-none text-danger small fw-bold">Check Requests <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden border-bottom border-4 border-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted text-uppercase small fw-bold mb-1">Rating</p>
                    <h3 class="text-white fw-bold mb-0"><?= $p['rating'] ?>/5.0</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fas fa-star fa-lg"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-warning small">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-white m-0">Revenue Analytics</h5>
                <select class="form-select w-auto py-1 px-3 text-small bg-dark text-white border-secondary">
                    <option>Last 6 Months</option>
                </select>
            </div>
            <div style="height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4">Quick Actions</h5>
            
            <div class="d-grid gap-3">
                <a href="new_jobs.php" class="btn btn-outline-light p-3 text-start d-flex align-items-center gap-3 hover-scale">
                    <div class="bg-primary bg-opacity-25 p-2 rounded-circle text-primary"><i class="fas fa-search"></i></div>
                    <div>
                        <div class="fw-bold">Find New Jobs</div>
                        <small class="text-muted">Browse available requests</small>
                    </div>
                </a>

                <a href="wallet.php" class="btn btn-outline-light p-3 text-start d-flex align-items-center gap-3 hover-scale">
                    <div class="bg-success bg-opacity-25 p-2 rounded-circle text-success"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="fw-bold">Withdraw Earnings</div>
                        <small class="text-muted">Transfer to bank account</small>
                    </div>
                </a>

                <a href="profile.php" class="btn btn-outline-light p-3 text-start d-flex align-items-center gap-3 hover-scale">
                    <div class="bg-info bg-opacity-25 p-2 rounded-circle text-info"><i class="fas fa-user-edit"></i></div>
                    <div>
                        <div class="fw-bold">Update Profile</div>
                        <small class="text-muted">Manage account details</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Gradient Effect
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Earnings (SAR)',
                data: <?= json_encode($income_data) ?>,
                borderColor: '#6366f1',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();

// Toggle Status Logic
if(isset($_GET['status_toggle'])) {
    $new = $p['is_online'] ? 0 : 1;
    $conn->query("UPDATE users SET is_online=$new WHERE id=$pid");
    echo "<script>location.href='dashboard.php'</script>";
}

// Analytics Data
$pending = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status='assigned'")->fetch_row()[0];
$completed = $conn->query("SELECT COUNT(*) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0];
$earnings = $conn->query("SELECT SUM(final_total) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0] ?? 0;

// Chart Data (Mockup for last 6 months)
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$chart_data = [1200, 1900, 3000, 500, 2000, $earnings > 0 ? $earnings : 500]; 
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold text-white mb-1">Dashboard</h2>
        <p class="text-gray mb-0">Welcome back, <?= $p['name'] ?>! Here's what's happening.</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill glass-card">
            <span class="badge rounded-circle p-1 <?= $p['is_online']?'bg-success':'bg-danger' ?>"> </span>
            <span class="small fw-bold <?= $p['is_online']?'text-success':'text-danger' ?>">
                <?= $p['is_online'] ? 'AVAILABLE' : 'OFFLINE' ?>
            </span>
        </div>
        <a href="dashboard.php?status_toggle=1" class="btn btn-primary rounded-pill px-4 shadow-lg">
            <i class="fas fa-power-off me-2"></i> Toggle Status
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 p-4 opacity-25">
                <i class="fas fa-wallet fa-4x text-primary"></i>
            </div>
            <p class="text-gray text-uppercase small fw-bold mb-2">Total Earnings</p>
            <h2 class="text-white fw-bold mb-1">SAR <?= number_format($earnings, 2) ?></h2>
            <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2">
                <i class="fas fa-arrow-up small me-1"></i> +12.5%
            </span>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 p-4 opacity-25">
                <i class="fas fa-briefcase fa-4x text-info"></i>
            </div>
            <p class="text-gray text-uppercase small fw-bold mb-2">Completed Jobs</p>
            <h2 class="text-white fw-bold mb-1"><?= $completed ?> Jobs</h2>
            <span class="text-gray small">Lifetime orders</span>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <p class="text-gray text-uppercase small fw-bold mb-2">Average Rating</p>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="text-white fw-bold mb-0"><?= $p['rating'] ?></h2>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                <i class="fas fa-trophy fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-white m-0">Revenue Analytics</h5>
                <select class="form-select w-auto py-1 px-3 text-small bg-transparent">
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
            <h5 class="fw-bold text-white mb-4">Live Status</h5>
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                    <i class="fas fa-clock fa-xl"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-white m-0"><?= $pending ?></h4>
                    <span class="text-gray small">Pending/Active Jobs</span>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <h6 class="text-gray text-uppercase small fw-bold mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="new_jobs.php" class="btn btn-outline-light text-start">
                    <i class="fas fa-bell me-2 text-warning"></i> View Requests
                </a>
                <a href="wallet.php" class="btn btn-outline-light text-start">
                    <i class="fas fa-money-bill-wave me-2 text-success"></i> Withdraw Money
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Modern Chart Configuration
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create Gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Earnings (SAR)',
                data: <?= json_encode($chart_data) ?>,
                borderColor: '#6366f1',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointRadius: 4,
                pointHoverRadius: 6,
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
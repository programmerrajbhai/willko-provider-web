<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// --- 1. PROGRESS UPDATE LOGIC ---
if(isset($_POST['update_status'])) {
    $bid = $_POST['bid'];
    $current_status = $_POST['current_status'];
    $next_status = '';

    // Status Flow Logic
    if ($current_status == 'assigned') $next_status = 'on_way';
    elseif ($current_status == 'on_way') $next_status = 'started';
    elseif ($current_status == 'started') $next_status = 'completed';

    if($next_status) {
        $conn->query("UPDATE bookings SET status='$next_status' WHERE id=$bid AND provider_id=$pid");
        echo "<script>location.href='my_jobs.php';</script>";
    }
}

// --- 2. FETCH ACTIVE JOBS ---
$sql = "SELECT * FROM bookings 
        WHERE provider_id=$pid AND status IN ('assigned', 'on_way', 'started') 
        ORDER BY schedule_date ASC";
$res = $conn->query($sql);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">Active Jobs</h3>
        <p class="text-gray small mb-0">Manage your ongoing tasks step-by-step</p>
    </div>
</div>

<div class="row g-4">
<?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): 
    // Dynamic UI Variables based on Status
    $status = $row['status'];
    $progress_width = 25;
    $btn_text = "Start Traveling";
    $btn_class = "btn-info";
    $btn_icon = "fa-motorcycle";
    $status_msg = "Job Assigned";

    if($status == 'on_way') { 
        $progress_width = 60; 
        $btn_text = "Arrived & Start Work"; 
        $btn_class = "btn-warning"; 
        $btn_icon = "fa-tools";
        $status_msg = "You are on the way";
    }
    if($status == 'started') { 
        $progress_width = 90; 
        $btn_text = "Complete Job & Collect Cash"; 
        $btn_class = "btn-success"; 
        $btn_icon = "fa-check-circle";
        $status_msg = "Work in Progress";
    }
?>
    <div class="col-lg-6">
        <div class="glass-card p-0 border-start border-4 border-primary h-100">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="text-white fw-bold mb-1"><?= $row['contact_name'] ?></h5>
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                            <?= $status_msg ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <h4 class="text-primary fw-bold mb-0">SAR <?= number_format($row['final_total']) ?></h4>
                        <small class="text-gray text-uppercase" style="font-size:10px;"><?= strtoupper($row['payment_method']) ?></small>
                    </div>
                </div>

                <div class="d-flex justify-content-between text-gray small mb-1" style="font-size: 10px;">
                    <span>Assigned</span>
                    <span>Traveling</span>
                    <span>Working</span>
                    <span>Done</span>
                </div>
                <div class="progress bg-dark bg-opacity-50 mb-4" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-gradient-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $progress_width ?>%"></div>
                </div>

                <div class="bg-dark bg-opacity-40 p-3 rounded-3 mb-3 border border-white border-opacity-5">
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <i class="fas fa-map-marker-alt text-danger mt-1"></i>
                        <div>
                            <span class="text-gray small text-uppercase fw-bold d-block" style="font-size: 10px;">Location</span>
                            <p class="text-white small mb-0 lh-sm"><?= $row['full_address'] ?></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-phone-alt text-success"></i>
                        <a href="tel:<?= $row['contact_phone'] ?>" class="text-white small text-decoration-none fw-bold"><?= $row['contact_phone'] ?></a>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <a href="tel:<?= $row['contact_phone'] ?>" class="btn btn-outline-light w-100 btn-sm rounded-3">
                            <i class="fas fa-phone me-1"></i> Call
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($row['full_address']) ?>" target="_blank" class="btn btn-outline-light w-100 btn-sm rounded-3">
                            <i class="fas fa-location-arrow me-1"></i> Map
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="provider_order_details.php?id=<?= $row['id'] ?>" class="btn btn-outline-light w-100 btn-sm rounded-3">
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" class="p-3 bg-dark bg-opacity-50 border-top border-white border-opacity-10">
                <input type="hidden" name="bid" value="<?= $row['id'] ?>">
                <input type="hidden" name="current_status" value="<?= $status ?>">
                
                <button type="submit" name="update_status" class="btn <?= $btn_class ?> w-100 fw-bold shadow-lg py-2" onclick="return confirm('Are you sure you want to proceed to next step?');">
                    <i class="fas <?= $btn_icon ?> me-2"></i> <?= $btn_text ?>
                </button>
            </form>
        </div>
    </div>
<?php endwhile; else: ?>
    <div class="col-12 text-center py-5">
        <div class="glass-card p-5 d-inline-block">
            <div class="mb-3 text-secondary opacity-50"><i class="fas fa-clipboard-check fa-4x"></i></div>
            <h4 class="text-white">No Active Jobs</h4>
            <p class="text-gray small">Check 'New Requests' to start earning.</p>
            <a href="new_jobs.php" class="btn btn-primary mt-3 rounded-pill px-4">Find New Jobs</a>
        </div>
    </div>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
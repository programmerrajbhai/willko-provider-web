<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// --- STATUS UPDATE LOGIC ---
if(isset($_POST['update_status'])) {
    $bid = $_POST['bid'];
    $current_status = $_POST['current_status'];
    $next_status = '';
    $msg = '';

    // Flow: Assigned -> On Way -> Started -> Completed
    if ($current_status == 'assigned') {
        $next_status = 'on_way';
        $msg = "Status Updated: You are on the way!";
    } elseif ($current_status == 'on_way') {
        $next_status = 'started';
        $msg = "Status Updated: Work Started!";
    } elseif ($current_status == 'started') {
        $next_status = 'completed';
        $msg = "Great! Job Completed Successfully.";
    }

    if($next_status) {
        $conn->query("UPDATE bookings SET status='$next_status' WHERE id=$bid AND provider_id=$pid");
        echo "<script>alert('$msg'); window.location.href='my_jobs.php';</script>";
    }
}

// Fetch Active Jobs
$sql = "SELECT b.*, u.name as c_name, u.phone as c_phone, u.image as c_image 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.provider_id=$pid AND b.status IN ('assigned', 'on_way', 'started') 
        ORDER BY b.schedule_date ASC";
$res = $conn->query($sql);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">My Active Jobs</h3>
        <p class="text-muted small mb-0">Manage tasks & update progress</p>
    </div>
</div>

<div class="row g-4">
<?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): 
    // Logic for Buttons & Progress
    $st = $row['status'];
    $progress = 20;
    $btn_text = "Start Traveling";
    $btn_cls = "btn-info";
    $badge_cls = "bg-primary";
    
    if($st == 'on_way') { 
        $progress = 50; 
        $btn_text = "Arrived & Start Work"; 
        $btn_cls = "btn-warning"; 
        $badge_cls = "bg-warning text-dark";
    }
    if($st == 'started') { 
        $progress = 80; 
        $btn_text = "Complete & Deliver"; 
        $btn_cls = "btn-success"; 
        $badge_cls = "bg-success";
    }

    $img = !empty($row['c_image']) ? "../api/uploads/".$row['c_image'] : "https://ui-avatars.com/api/?name=".$row['c_name']."&background=random";
?>
    <div class="col-lg-6">
        <div class="glass-card h-100 overflow-hidden">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?= $img ?>" class="rounded-circle border border-secondary" width="50" height="50">
                        <div>
                            <h5 class="text-white fw-bold mb-1"><?= $row['c_name'] ?></h5>
                            <span class="badge <?= $badge_cls ?> border border-white border-opacity-10">
                                <?= strtoupper(str_replace('_', ' ', $st)) ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h4 class="text-primary fw-bold mb-0">SAR <?= number_format($row['final_total']) ?></h4>
                        <small class="text-muted"><?= strtoupper($row['payment_method']) ?></small>
                    </div>
                </div>

                <div class="progress bg-dark mb-4" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" style="width: <?= $progress ?>%"></div>
                </div>

                <div class="p-3 rounded-3 mb-3" style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex gap-2 mb-2">
                        <i class="fas fa-map-marker-alt text-danger mt-1"></i>
                        <span class="text-white small"><?= $row['full_address'] ?></span>
                    </div>
                    <div class="d-flex gap-2">
                        <i class="fas fa-clock text-info mt-1"></i>
                        <span class="text-muted small"><?= date('d M, Y', strtotime($row['schedule_date'])) ?> • <?= $row['schedule_time'] ?></span>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-4">
                        <a href="tel:<?= $row['c_phone'] ?>" class="btn btn-dark border-secondary w-100 btn-sm text-light">
                            <i class="fas fa-phone me-1"></i> Call
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="http://maps.google.com/?q=<?= urlencode($row['full_address']) ?>" target="_blank" class="btn btn-dark border-secondary w-100 btn-sm text-light">
                            <i class="fas fa-map me-1"></i> Map
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="provider_order_details.php?id=<?= $row['id'] ?>" class="btn btn-dark border-secondary w-100 btn-sm text-light">
                            Details
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" class="p-3 border-top border-secondary border-opacity-25" style="background: rgba(0,0,0,0.2);">
                <input type="hidden" name="bid" value="<?= $row['id'] ?>">
                <input type="hidden" name="current_status" value="<?= $st ?>">
                
                <button type="submit" name="update_status" class="btn <?= $btn_cls ?> w-100 fw-bold py-2 shadow-sm" onclick="return confirm('Are you sure regarding this step?');">
                    <i class="fas fa-arrow-right me-2"></i> <?= $btn_text ?>
                </button>
            </form>
        </div>
    </div>
<?php endwhile; else: ?>
    <div class="col-12 text-center py-5">
        <div class="glass-card p-5">
            <i class="fas fa-clipboard-check fa-4x text-muted mb-3 opacity-50"></i>
            <h4 class="text-white">No Active Jobs</h4>
            <p class="text-muted">You have no ongoing tasks. Check requests.</p>
            <a href="new_jobs.php" class="btn btn-primary rounded-pill px-4 mt-2">Find Jobs</a>
        </div>
    </div>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
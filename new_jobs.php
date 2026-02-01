<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// Accept Job Logic
if(isset($_POST['accept_job'])) {
    $bid = $_POST['booking_id'];
    $conn->query("UPDATE bookings SET provider_id=$pid, status='assigned' WHERE id=$bid AND provider_id IS NULL");
    echo "<script>location.href='my_jobs.php?msg=Job Accepted';</script>";
}

// Fetch Pending Jobs (No provider assigned yet)
// Logic: Show jobs that match provider category (Optional) or All Open Jobs
$sql = "SELECT b.*, u.name as c_name, u.address as c_address 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.status='pending' AND b.provider_id IS NULL 
        ORDER BY b.created_at DESC";
$res = $conn->query($sql);
?>

<h4 class="fw-bold text-white mb-4">New Job Requests</h4>

<div class="row g-3">
    <?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): ?>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <span class="badge bg-primary bg-opacity-25 text-primary border border-primary">New Request</span>
                <span class="text-white fw-bold">SAR <?= number_format($row['final_total']) ?></span>
            </div>
            <h5 class="text-white fw-bold mb-1">Service Order #<?= $row['id'] ?></h5>
            <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt me-1"></i> <?= $row['c_address'] ?></p>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <small class="text-secondary"><?= date('d M, h:i A', strtotime($row['schedule_date'])) ?></small>
                <form method="POST">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="accept_job" class="btn btn-primary btn-sm px-4 rounded-pill">Accept</button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; else: ?>
        <div class="text-center text-muted py-5"><i class="fas fa-inbox fa-3x mb-3"></i><p>No new jobs available currently.</p></div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// Status Update Logic
if(isset($_POST['update_status'])) {
    $bid = $_POST['bid'];
    $st = $_POST['status'];
    $conn->query("UPDATE bookings SET status='$st' WHERE id=$bid AND provider_id=$pid");
    echo "<script>location.href='my_jobs.php';</script>";
}

// Fetch Active Jobs
$sql = "SELECT b.*, u.name as c_name, u.phone as c_phone, u.address as c_address 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.provider_id=$pid AND b.status IN ('assigned', 'on_way', 'started') 
        ORDER BY b.schedule_date ASC";
$res = $conn->query($sql);
?>

<h4 class="fw-bold text-white mb-4">My Active Jobs</h4>

<?php while($row = $res->fetch_assoc()): ?>
<div class="glass-card p-3 mb-3 border-start border-4 border-primary">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5 class="text-white fw-bold mb-1"><?= $row['c_name'] ?></h5>
            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i> <?= $row['c_address'] ?></p>
            <a href="tel:<?= $row['c_phone'] ?>" class="btn btn-outline-light btn-sm rounded-pill"><i class="fas fa-phone me-1"></i> Call Customer</a>
        </div>
        <div class="text-end">
            <h4 class="text-primary fw-bold mb-0">SAR <?= $row['final_total'] ?></h4>
            <span class="badge bg-warning text-dark mt-2"><?= ucfirst(str_replace('_', ' ', $row['status'])) ?></span>
        </div>
    </div>
    <hr class="border-secondary opacity-25">
    
    <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="bid" value="<?= $row['id'] ?>">
        <?php if($row['status'] == 'assigned'): ?>
            <button name="status" value="on_way" class="btn btn-info w-100 fw-bold">Start Travel</button>
        <?php elseif($row['status'] == 'on_way'): ?>
            <button name="status" value="started" class="btn btn-warning w-100 fw-bold">Start Job</button>
        <?php elseif($row['status'] == 'started'): ?>
            <button name="status" value="completed" class="btn btn-success w-100 fw-bold">Complete Job</button>
        <?php endif; ?>
    </form>
</div>
<?php endwhile; ?>

<?php include 'includes/footer.php'; ?>
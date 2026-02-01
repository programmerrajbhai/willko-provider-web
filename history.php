<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// Fetch History (Completed or Cancelled)
$sql = "SELECT b.*, u.name as c_name, u.address as c_address 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.provider_id=$pid AND b.status IN ('completed', 'cancelled') 
        ORDER BY b.schedule_date DESC";
$res = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-white mb-0">Job History</h4>
    <div class="badge bg-dark border border-secondary p-2">Total: <?= $res->num_rows ?></div>
</div>

<div class="row g-3">
    <?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): 
        $st = $row['status'];
        $badge_color = $st == 'completed' ? 'success' : 'danger';
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-4 h-100 position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 h-100 bg-<?= $badge_color ?>" style="width: 4px;"></div>
            
            <div class="d-flex justify-content-between align-items-start mb-2 ps-2">
                <div>
                    <span class="text-secondary small">#ORD-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></span>
                    <h5 class="text-white fw-bold mb-0"><?= $row['c_name'] ?></h5>
                </div>
                <span class="badge bg-soft-<?= $badge_color ?> text-<?= $badge_color ?> border border-<?= $badge_color ?>">
                    <?= ucfirst($st) ?>
                </span>
            </div>

            <p class="text-muted small ps-2 mb-3">
                <i class="fas fa-calendar-check me-1"></i> <?= date('d M, Y', strtotime($row['schedule_date'])) ?>
            </p>

            <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-25 pt-3 ps-2">
                <h5 class="text-white fw-bold mb-0">SAR <?= number_format($row['final_total']) ?></h5>
                <a href="provider_order_details.php?id=<?= $row['id'] ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    View Details <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endwhile; else: ?>
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-white">No history found!</h5>
                <p class="text-muted">You haven't completed any jobs yet.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
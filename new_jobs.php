<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// --- 1. ACCEPT JOB LOGIC ---
if(isset($_POST['accept_job'])) {
    $bid = $_POST['booking_id'];
    
    // চেক: জবটি কি এখনো পেনন্ডিং এবং এই প্রোভাইডারের নামেই আছে?
    $check = $conn->query("SELECT id FROM bookings WHERE id=$bid AND status='pending' AND provider_id=$pid");
    
    if($check->num_rows > 0) {
        // স্ট্যাটাস 'assigned' হলো -> মানে কাজ কনফার্ম
        $conn->query("UPDATE bookings SET status='assigned' WHERE id=$bid");
        
        echo "<script>
            // সুইট অ্যালার্ট বা সিম্পল অ্যালার্ট
            alert('Job Accepted! It is now in your Active Jobs.');
            window.location.href='my_jobs.php';
        </script>";
    } else {
        echo "<script>alert('Error: This job offer has expired.'); window.location.href='new_jobs.php';</script>";
    }
}

// --- 2. REJECT JOB LOGIC ---
if(isset($_POST['reject_job'])) {
    $bid = $_POST['booking_id'];
    
    // রিজেক্ট করলে provider_id NULL করে দেওয়া হলো
    $conn->query("UPDATE bookings SET provider_id=NULL WHERE id=$bid AND provider_id=$pid");
    
    echo "<script>
        alert('You have declined this job.');
        window.location.href='new_jobs.php';
    </script>";
}

// --- 3. FETCH DIRECT REQUESTS ONLY ---
// শুধুমাত্র অ্যাডমিন যেগুলো এই প্রোভাইডারকে দিয়েছে
$sql = "SELECT b.*, u.name as c_name, u.image as c_image, u.phone as c_phone 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.status='pending' AND b.provider_id=$pid 
        ORDER BY b.created_at DESC";
$res = $conn->query($sql);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">Direct Job Requests</h3>
        <p class="text-gray small mb-0">Jobs assigned specifically to you by Admin</p>
    </div>
    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary px-3 py-2 rounded-pill">
        <?= $res->num_rows ?> Pending
    </span>
</div>

<div class="row g-4">
    <?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): 
        
        // সার্ভিস লিস্ট বের করা
        $bid = $row['id'];
        $items = $conn->query("SELECT service_name FROM booking_items WHERE booking_id=$bid LIMIT 2");
        $services = [];
        while($s = $items->fetch_assoc()) $services[] = $s['service_name'];
        $svc_text = implode(", ", $services) . ($items->num_rows > 1 ? "..." : "");
        
        $img = !empty($row['c_image']) ? "../api/uploads/".$row['c_image'] : "https://ui-avatars.com/api/?name=".$row['c_name']."&background=random";
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-0 h-100 d-flex flex-column overflow-hidden position-relative hover-scale border-start border-4 border-info">
            
            <div class="position-absolute top-0 end-0 bg-info text-dark px-3 py-1 rounded-bottom-start fw-bold shadow-sm" style="z-index: 10; font-size: 12px;">
                <i class="fas fa-user-check me-1"></i> Assigned to You
            </div>

            <div class="p-4 flex-grow-1">
                <div class="mb-3">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                        #ORD-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>
                    </span>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-white border-opacity-10">
                    <img src="<?= $img ?>" class="rounded-circle border border-2 border-primary shadow-sm" width="45" height="45" style="object-fit: cover;">
                    <div>
                        <h6 class="text-white fw-bold mb-0"><?= $row['c_name'] ?></h6>
                        <small class="text-gray" style="font-size: 11px;">New Customer</small>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex gap-2 text-gray mb-2 align-items-center">
                        <i class="fas fa-tools text-primary small"></i>
                        <span class="text-white small fw-bold text-truncate"><?= $svc_text ?></span>
                    </div>
                    
                    <div class="d-flex gap-2 text-gray mb-2 align-items-start">
                        <i class="fas fa-map-marker-alt text-danger small mt-1"></i>
                        <span class="text-white small lh-sm text-truncate" style="max-width: 250px;">
                            <?= !empty($row['full_address']) ? $row['full_address'] : 'Address hidden until accepted' ?>
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2 text-gray align-items-center">
                        <i class="fas fa-clock text-warning small"></i>
                        <span class="text-white small">
                            <?= date('d M, Y', strtotime($row['schedule_date'])) ?> • <?= $row['schedule_time'] ?>
                        </span>
                    </div>
                </div>
                
                <h5 class="text-success fw-bold mt-3 mb-0">SAR <?= number_format($row['final_total']) ?></h5>
            </div>

            <div class="p-3 bg-dark bg-opacity-50 d-flex gap-2 border-top border-white border-opacity-5">
                
                <form method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to REJECT this job? It will be removed from your list.');">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="reject_job" class="btn btn-outline-danger w-100 btn-sm rounded-pill fw-bold">
                        Decline
                    </button>
                </form>

                <form method="POST" class="flex-fill">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="accept_job" class="btn btn-primary w-100 btn-sm rounded-pill fw-bold shadow-lg">
                        Accept
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; else: ?>
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5 d-inline-block">
                <i class="fas fa-inbox fa-4x text-gray mb-3 opacity-25"></i>
                <h4 class="text-white">No Direct Requests</h4>
                <p class="text-gray small">You don't have any pending job offers from Admin.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
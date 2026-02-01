<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];

// --- 1. ACCEPT JOB LOGIC ---
if(isset($_POST['accept_job'])) {
    $bid = $_POST['booking_id'];
    
    // শুধু সেই জব একসেপ্ট হবে যেটা 'pending' এবং কাউকে দেওয়া হয়নি
    $check = $conn->query("SELECT id FROM bookings WHERE id=$bid AND status='pending' AND provider_id IS NULL");
    
    if($check->num_rows > 0) {
        // স্ট্যাটাস 'assigned' এ আপডেট করা হলো
        $conn->query("UPDATE bookings SET provider_id=$pid, status='assigned' WHERE id=$bid");
        
        // (Optional) নোটিফিকেশন সিস্টেম থাকলে এখানে ইউজারকে নোটিফাই করা যাবে
        echo "<script>
            alert('Job Accepted Successfully! Check My Jobs.');
            location.href='my_jobs.php';
        </script>";
    } else {
        echo "<script>alert('Sorry, this job is no longer available.'); location.href='new_jobs.php';</script>";
    }
}

// --- 2. FETCH PENDING JOBS ---
// নিজের ক্যাটাগরির কাজগুলো আগে দেখাবে (যদি ক্যাটাগরি ফিল্টার করতে চান)
$sql = "SELECT b.*, s.image as service_image 
        FROM bookings b 
        LEFT JOIN services s ON b.service_id = s.id
        WHERE b.status='pending' AND b.provider_id IS NULL 
        ORDER BY b.created_at DESC";
$res = $conn->query($sql);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">New Requests</h3>
        <p class="text-gray small mb-0">Opportunities available for you</p>
    </div>
    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary px-3 py-2 rounded-pill">
        <?= $res->num_rows ?> Available
    </span>
</div>

<div class="row g-4">
    <?php if($res->num_rows > 0): while($row = $res->fetch_assoc()): 
        // Service Items Summary
        $bid = $row['id'];
        $items = $conn->query("SELECT service_name FROM booking_items WHERE booking_id=$bid LIMIT 2");
        $services = [];
        while($s = $items->fetch_assoc()) $services[] = $s['service_name'];
        $svc_text = implode(", ", $services) . ($items->num_rows > 1 ? "..." : "");
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-0 h-100 d-flex flex-column overflow-hidden position-relative hover-scale">
            
            <div class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 rounded-bottom-start fw-bold shadow-sm" style="z-index: 10;">
                SAR <?= number_format($row['final_total']) ?>
            </div>

            <div class="p-4 flex-grow-1">
                <div class="mb-3">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                        #ORD-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>
                    </span>
                </div>

                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="bg-primary bg-opacity-25 p-2 rounded-circle text-primary">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-white fw-bold mb-1">Service Location</h6>
                        <p class="text-gray small mb-0 lh-sm text-truncate" style="max-width: 250px;">
                            <?= $row['full_address'] ?>
                        </p>
                    </div>
                </div>

                <div class="bg-dark bg-opacity-40 p-3 rounded-3 mb-2">
                    <div class="d-flex gap-2 text-gray mb-2">
                        <i class="fas fa-tools mt-1 text-info"></i>
                        <span class="text-white small fw-bold"><?= $svc_text ?></span>
                    </div>
                    <div class="d-flex gap-2 text-gray">
                        <i class="fas fa-clock mt-1 text-warning"></i>
                        <span class="text-white small">
                            <?= date('d M, Y', strtotime($row['schedule_date'])) ?> • <?= $row['schedule_time'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark bg-opacity-50 d-flex gap-2 border-top border-white border-opacity-5">
                <a href="provider_order_details.php?id=<?= $row['id'] ?>&preview=true" class="btn btn-outline-light flex-fill btn-sm rounded-pill fw-bold">
                    View Details
                </a>
                <form method="POST" class="flex-fill">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="accept_job" class="btn btn-primary w-100 btn-sm rounded-pill fw-bold shadow-lg">
                        Accept Job
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; else: ?>
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5 d-inline-block">
                <div class="mb-3 text-secondary opacity-50"><i class="fas fa-search-location fa-4x"></i></div>
                <h4 class="text-white">No New Requests</h4>
                <p class="text-gray small">Relax! You will be notified when a job matches your profile.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
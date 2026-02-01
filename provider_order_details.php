<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

if(!isset($_GET['id'])) { echo "<script>location.href='dashboard.php';</script>"; exit(); }
$bid = $_GET['id'];
$pid = $_SESSION['provider_id'];

$is_preview = isset($_GET['preview']) && $_GET['preview'] == 'true';

// Fetch Booking
$sql = "SELECT * FROM bookings WHERE id=$bid"; 
if(!$is_preview) { $sql .= " AND provider_id=$pid"; } // Security check

$order = $conn->query($sql)->fetch_assoc();

if(!$order) { 
    echo "<div class='text-center text-white mt-5'><h3>Access Denied</h3><a href='dashboard.php' class='btn btn-light'>Go Home</a></div>"; 
    include 'includes/footer.php'; exit(); 
}

// Fetch Items
$items = $conn->query("SELECT * FROM booking_items WHERE booking_id=$bid");
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <a href="javascript:history.back()" class="text-gray text-decoration-none small mb-1 d-block"><i class="fas fa-arrow-left me-1"></i> Back</a>
        <h3 class="fw-bold text-white mb-0">Job Details <span class="text-primary">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></span></h3>
    </div>
    <div class="d-flex gap-2">
        <?php if($is_preview): ?>
            <form method="POST" action="new_jobs.php">
                <input type="hidden" name="booking_id" value="<?= $order['id'] ?>">
                <button type="submit" name="accept_job" class="btn btn-primary px-4 fw-bold shadow-lg">Accept Job</button>
            </form>
        <?php else: ?>
            <button onclick="window.print()" class="btn btn-outline-light"><i class="fas fa-print me-2"></i> Print</button>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-10 pb-4 mb-4">
                <div>
                    <span class="text-gray small text-uppercase fw-bold">Current Status</span><br>
                    <span class="badge bg-info text-dark text-uppercase mt-2 px-3 py-2"><?= str_replace('_', ' ', $order['status']) ?></span>
                </div>
                <div class="text-end">
                    <span class="text-gray small text-uppercase fw-bold">Schedule</span>
                    <h6 class="text-white fw-bold mb-0 mt-1"><?= date('d M, Y', strtotime($order['schedule_date'])) ?></h6>
                    <small class="text-primary font-monospace"><?= $order['schedule_time'] ?></small>
                </div>
            </div>

            <h6 class="text-white fw-bold mb-3 ps-2 border-start border-3 border-primary">Services</h6>
            <div class="table-responsive rounded-3 overflow-hidden mb-4">
                <table class="table text-gray align-middle mb-0" style="border-color: rgba(255,255,255,0.05);">
                    <thead class="bg-dark bg-opacity-50">
                        <tr class="text-uppercase small text-white">
                            <th class="py-3 ps-3">Service Name</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end pe-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-3 text-white fw-bold"><?= $item['service_name'] ?></td>
                            <td class="text-center">SAR <?= number_format($item['unit_price']) ?></td>
                            <td class="text-center">x<?= $item['quantity'] ?></td>
                            <td class="text-end pe-3 text-white fw-bold">SAR <?= number_format($item['total_price']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="bg-dark bg-opacity-25">
                        <tr>
                            <td colspan="3" class="text-end pt-3 text-gray">Subtotal</td>
                            <td class="text-end pt-3 pe-3 text-gray">SAR <?= number_format($order['sub_total']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-success border-0">Discount</td>
                            <td class="text-end text-success border-0 pe-3">- SAR <?= number_format($order['discount_amount']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-white fw-bold fs-5 border-0 pb-3">Grand Total</td>
                            <td class="text-end text-primary fw-bold fs-5 border-0 pb-3 pe-3">SAR <?= number_format($order['final_total']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <?php if(!empty($order['order_note'])): ?>
            <div class="p-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3">
                <small class="text-warning text-uppercase fw-bold mb-1 d-block">Customer Note:</small>
                <p class="text-gray small mb-0 fst-italic">"<?= $order['order_note'] ?>"</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-4">
            <h6 class="text-gray text-uppercase small fw-bold mb-4 ps-2 border-start border-3 border-info">Customer Info</h6>
            
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-user fa-2x text-primary"></i>
                </div>
                <h5 class="text-white fw-bold mb-0"><?= $order['contact_name'] ?></h5>
                <small class="text-success"><i class="fas fa-check-circle me-1"></i> Contact Person</small>
            </div>

            <?php if(!$is_preview): ?>
            <div class="d-grid gap-2 mb-4">
                <a href="tel:<?= $order['contact_phone'] ?>" class="btn btn-outline-light w-100">
                    <i class="fas fa-phone me-2 text-success"></i> <?= $order['contact_phone'] ?>
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($order['full_address']) ?>" target="_blank" class="btn btn-primary w-100 shadow-sm">
                    <i class="fas fa-location-arrow me-2"></i> Get Directions
                </a>
            </div>
            <?php else: ?>
                <div class="alert alert-info bg-opacity-10 border-0 small text-center mb-4">
                    <i class="fas fa-lock me-1"></i> Accept job to view contact details.
                </div>
            <?php endif; ?>

            <div class="bg-dark bg-opacity-40 p-3 rounded-3 border border-white border-opacity-5">
                <h6 class="text-white fw-bold small mb-2 text-uppercase">Service Address</h6>
                <p class="text-gray small mb-0 lh-sm">
                    <i class="fas fa-map-pin me-2 text-danger"></i> 
                    <?= $order['full_address'] ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
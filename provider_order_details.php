<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

if(!isset($_GET['id'])) { echo "<script>location.href='dashboard.php';</script>"; exit(); }
$bid = $_GET['id'];
$pid = $_SESSION['provider_id'];
$is_preview = isset($_GET['preview']) && $_GET['preview'] == 'true';

// Fetch Query
$sql = "SELECT b.*, u.name as c_name, u.phone as c_phone, u.image as c_image 
        FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.id=$bid"; 
if(!$is_preview) { $sql .= " AND provider_id=$pid"; } 

$order = $conn->query($sql)->fetch_assoc();
if(!$order) { echo "<script>location.href='dashboard.php';</script>"; exit(); }

$items = $conn->query("SELECT * FROM booking_items WHERE booking_id=$bid");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="javascript:history.back()" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i> Back</a>
        <h3 class="fw-bold text-white mt-1">Order #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></h3>
    </div>
    <?php if($is_preview): ?>
        <form action="new_jobs.php" method="POST">
            <input type="hidden" name="booking_id" value="<?= $order['id'] ?>">
            <button type="submit" name="accept_job" class="btn btn-primary px-4 fw-bold">Accept Job</button>
        </form>
    <?php else: ?>
        <button onclick="window.print()" class="btn btn-dark border-secondary text-light"><i class="fas fa-print me-2"></i> Print</button>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 pb-3 mb-3">
                <div>
                    <small class="text-muted text-uppercase">Status</small><br>
                    <span class="badge bg-primary"><?= strtoupper(str_replace('_',' ',$order['status'])) ?></span>
                </div>
                <div class="text-end">
                    <small class="text-muted text-uppercase">Schedule</small><br>
                    <span class="text-white fw-bold"><?= date('d M, Y', strtotime($order['schedule_date'])) ?></span>
                    <span class="text-info small ms-1"><?= $order['schedule_time'] ?></span>
                </div>
            </div>

            <h6 class="text-white fw-bold mb-3 border-start border-3 border-primary ps-2">Services</h6>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Service</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td class="text-white fw-bold"><?= $item['service_name'] ?></td>
                            <td class="text-center text-muted">SAR <?= $item['unit_price'] ?></td>
                            <td class="text-center text-white">x<?= $item['quantity'] ?></td>
                            <td class="text-end text-white fw-bold">SAR <?= $item['total_price'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="border-top border-secondary border-opacity-25">
                        <tr>
                            <td colspan="3" class="text-end text-muted">Subtotal</td>
                            <td class="text-end text-white">SAR <?= number_format($order['sub_total']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-success">Discount</td>
                            <td class="text-end text-success">- SAR <?= number_format($order['discount_amount']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-white fw-bold fs-5">Grand Total</td>
                            <td class="text-end text-primary fw-bold fs-5">SAR <?= number_format($order['final_total']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-4">
            <h6 class="text-white fw-bold mb-4 border-start border-3 border-info ps-2">Customer</h6>
            <div class="text-center mb-4">
                <?php $img = !empty($order['c_image']) ? "../api/uploads/".$order['c_image'] : "https://ui-avatars.com/api/?name=".$order['c_name']; ?>
                <img src="<?= $img ?>" class="rounded-circle border border-2 border-secondary mb-2" width="70" height="70">
                <h5 class="text-white mb-0"><?= $order['c_name'] ?></h5>
            </div>
            
            <?php if(!$is_preview): ?>
            <div class="d-grid gap-2 mb-4">
                <a href="tel:<?= $order['c_phone'] ?>" class="btn btn-outline-light w-100">
                    <i class="fas fa-phone me-2 text-success"></i> Call Now
                </a>
                <a href="http://maps.google.com/?q=<?= urlencode($order['full_address']) ?>" target="_blank" class="btn btn-primary w-100">
                    <i class="fas fa-location-arrow me-2"></i> Navigate
                </a>
            </div>
            <?php endif; ?>

            <div class="p-3 rounded bg-dark border border-secondary border-opacity-25">
                <small class="text-muted text-uppercase fw-bold">Address</small>
                <p class="text-white small mb-0 mt-1"><?= $order['full_address'] ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
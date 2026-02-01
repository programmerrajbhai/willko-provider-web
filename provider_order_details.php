<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

if(!isset($_GET['id'])) { echo "<script>location.href='history.php';</script>"; exit(); }
$bid = $_GET['id'];
$pid = $_SESSION['provider_id'];

// Fetch Booking Details securely
$sql = "SELECT b.*, u.name as c_name, u.phone as c_phone, u.address as c_address, u.image as c_image 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.id=$bid AND b.provider_id=$pid";
$order = $conn->query($sql)->fetch_assoc();

if(!$order) { echo "<script>location.href='dashboard.php';</script>"; exit(); }

// Fetch Items
$items = $conn->query("SELECT * FROM booking_items WHERE booking_id=$bid");
?>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="history.php" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Back</a>
            <h4 class="fw-bold text-white mt-1">Order Details</h4>
        </div>
        <button onclick="window.print()" class="btn btn-dark border border-secondary text-light"><i class="fas fa-print me-2"></i> Print</button>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card p-4 mb-4">
                <div class="d-flex justify-content-between mb-4 border-bottom border-secondary border-opacity-25 pb-3">
                    <div>
                        <small class="text-muted text-uppercase">Order ID</small>
                        <h5 class="text-white fw-bold">#ORD-<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></h5>
                    </div>
                    <div class="text-end">
                        <small class="text-muted text-uppercase">Status</small><br>
                        <?php 
                            $badge = $order['status']=='completed'?'success':($order['status']=='cancelled'?'danger':'warning');
                        ?>
                        <span class="badge bg-soft-<?= $badge ?> text-<?= $badge ?> fs-6"><?= ucfirst($order['status']) ?></span>
                    </div>
                </div>

                <h6 class="text-primary mb-3"><i class="fas fa-list me-2"></i>Service Summary</h6>
                <div class="table-responsive">
                    <table class="table text-light align-middle" style="border-color: rgba(255,255,255,0.1);">
                        <thead class="bg-dark bg-opacity-50">
                            <tr>
                                <th>Service Name</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold d-block"><?= $item['service_name'] ?></span>
                                    <small class="text-muted">Standard Service</small>
                                </td>
                                <td class="text-center">SAR <?= number_format($item['unit_price']) ?></td>
                                <td class="text-center">x<?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold">SAR <?= number_format($item['total_price']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="border-top border-light">
                            <tr>
                                <td colspan="3" class="text-end text-muted">Sub Total</td>
                                <td class="text-end">SAR <?= number_format($order['sub_total']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end text-success">Discount</td>
                                <td class="text-end text-success">- SAR <?= number_format($order['discount_amount']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-white fs-5">Grand Total</td>
                                <td class="text-end fw-bold text-primary fs-5">SAR <?= number_format($order['final_total']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h6 class="text-info mb-4"><i class="fas fa-user-circle me-2"></i>Customer Info</h6>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h5 class="text-white fw-bold mb-0"><?= $order['c_name'] ?></h5>
                        <p class="text-muted small mb-0">Customer</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Phone Number</label>
                    <div class="d-flex align-items-center mt-1">
                        <i class="fas fa-phone-alt text-primary me-2"></i>
                        <span class="text-white"><?= $order['c_phone'] ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Service Address</label>
                    <div class="d-flex align-items-start mt-1">
                        <i class="fas fa-map-marker-alt text-danger me-2 mt-1"></i>
                        <span class="text-white" style="line-height: 1.4;"><?= $order['full_address'] ?? $order['c_address'] ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Schedule</label>
                    <div class="d-flex align-items-center mt-1">
                        <i class="fas fa-clock text-warning me-2"></i>
                        <span class="text-white">
                            <?= date('d M, Y', strtotime($order['schedule_date'])) ?> • <?= $order['schedule_time'] ?>
                        </span>
                    </div>
                </div>
                
                <?php if($order['status'] == 'completed'): ?>
                    <div class="alert alert-success bg-success bg-opacity-25 text-success border-0 text-center mt-4">
                        <i class="fas fa-check-circle me-1"></i> Job Completed Successfully
                    </div>
                <?php elseif($order['status'] == 'cancelled'): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-25 text-danger border-0 text-center mt-4">
                        <i class="fas fa-times-circle me-1"></i> Job Cancelled
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
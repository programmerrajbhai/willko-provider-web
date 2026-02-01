<?php include 'includes/header.php'; include 'includes/sidebar.php'; 
$pid = $_SESSION['provider_id'];
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();
?>
<div class="glass-card p-4 text-center mb-4">
    <small class="text-muted text-uppercase">Current Balance</small>
    <h1 class="text-white fw-bold display-4">SAR <?= number_format($p['balance'], 2) ?></h1>
    <button class="btn btn-primary rounded-pill px-4 mt-3">Withdraw Request</button>
</div>
<h5 class="text-white mb-3">Transaction History</h5>
<div class="glass-card p-3 text-center text-muted">No transactions found.</div>
<?php include 'includes/footer.php'; ?>
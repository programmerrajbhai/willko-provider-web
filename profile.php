<?php include 'includes/header.php'; include 'includes/sidebar.php';
$pid = $_SESSION['provider_id'];
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();
?>
<div class="glass-card p-4">
    <h4 class="text-white mb-4">Edit Profile</h4>
    <form>
        <label class="text-muted small">Full Name</label>
        <input type="text" class="form-control mb-3" value="<?= $p['name'] ?>">
        <label class="text-muted small">Phone</label>
        <input type="text" class="form-control mb-3" value="<?= $p['phone'] ?>">
        <button class="btn btn-primary">Save Changes</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
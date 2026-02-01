<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$pid = $_SESSION['provider_id'];
$msg = "";
$err = "";

// --- 1. UPDATE PERSONAL INFO ---
if(isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    
    // Image Upload Logic (Optional Basic Implementation)
    if(isset($_FILES['image']) && $_FILES['image']['name']) {
        $img_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../api/uploads/" . $img_name);
        $conn->query("UPDATE users SET image='$img_name' WHERE id=$pid");
    }

    if($conn->query("UPDATE users SET name='$name', phone='$phone', address='$address' WHERE id=$pid")) {
        $msg = "Profile updated successfully!";
    } else {
        $err = "Something went wrong!";
    }
}

// --- 2. UPDATE BANK INFO ---
if(isset($_POST['update_bank'])) {
    $bank = $conn->real_escape_string($_POST['bank_name']);
    $acc = $conn->real_escape_string($_POST['account_number']);
    $nid = $conn->real_escape_string($_POST['nid_number']);

    if($conn->query("UPDATE users SET bank_name='$bank', account_number='$acc', nid_number='$nid' WHERE id=$pid")) {
        $msg = "Bank details saved securely!";
    }
}

// --- 3. CHANGE PASSWORD ---
if(isset($_POST['change_pass'])) {
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];
    $confirm = $_POST['confirm_pass'];

    $check = $conn->query("SELECT password FROM users WHERE id=$pid")->fetch_assoc();
    
    if(password_verify($old, $check['password'])) {
        if($new === $confirm) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$hash' WHERE id=$pid");
            $msg = "Password changed successfully!";
        } else {
            $err = "New passwords do not match!";
        }
    } else {
        $err = "Old password is incorrect!";
    }
}

// Fetch Latest Data
$p = $conn->query("SELECT * FROM users WHERE id=$pid")->fetch_assoc();
$earnings = $conn->query("SELECT SUM(final_total) FROM bookings WHERE provider_id=$pid AND status='completed'")->fetch_row()[0] ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-white mb-1">Account Settings</h3>
        <p class="text-muted small mb-0">Manage your profile, banking & security</p>
    </div>
</div>

<?php if($msg): ?><div class="alert alert-success border-0 bg-success bg-opacity-25 text-white mb-4"><i class="fas fa-check-circle me-2"></i> <?= $msg ?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= $err ?></div><?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card p-4 text-center h-100">
            <div class="position-relative d-inline-block mb-3">
                <?php $img = !empty($p['image']) ? "../api/uploads/".$p['image'] : "https://ui-avatars.com/api/?name=".$p['name']; ?>
                <img src="<?= $img ?>" class="rounded-circle border border-4 border-dark shadow-lg" width="120" height="120" style="object-fit: cover;">
                <label for="imgUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow cursor-pointer" style="cursor: pointer;">
                    <i class="fas fa-camera small"></i>
                </label>
            </div>
            
            <h4 class="text-white fw-bold mb-1"><?= $p['name'] ?></h4>
            <p class="text-primary small mb-3 fw-bold"><?= $p['category'] ?? 'Service Provider' ?></p>
            
            <div class="d-flex justify-content-center gap-2 mb-4">
                <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i> <?= $p['rating'] ?> Rating</span>
                <span class="badge bg-<?= $p['status']=='active'?'success':'danger' ?>"><?= ucfirst($p['status']) ?></span>
            </div>

            <div class="row text-center border-top border-secondary border-opacity-25 pt-4">
                <div class="col-6 border-end border-secondary border-opacity-25">
                    <h5 class="text-white fw-bold mb-0">SAR <?= number_format($earnings) ?></h5>
                    <small class="text-muted" style="font-size: 10px;">TOTAL EARNED</small>
                </div>
                <div class="col-6">
                    <h5 class="text-white fw-bold mb-0"><?= date('M Y', strtotime($p['created_at'])) ?></h5>
                    <small class="text-muted" style="font-size: 10px;">JOINED SINCE</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="glass-card">
            <div class="card-header border-bottom border-secondary border-opacity-25 bg-transparent">
                <ul class="nav nav-pills card-header-pills gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4" id="pills-info-tab" data-bs-toggle="pill" data-bs-target="#pills-info" type="button">
                            <i class="fas fa-user-edit me-2"></i> Personal
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" id="pills-bank-tab" data-bs-toggle="pill" data-bs-target="#pills-bank" type="button">
                            <i class="fas fa-university me-2"></i> Bank & ID
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" id="pills-sec-tab" data-bs-toggle="pill" data-bs-target="#pills-sec" type="button">
                            <i class="fas fa-lock me-2"></i> Security
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4 tab-content" id="pills-tabContent">
                
                <div class="tab-pane fade show active" id="pills-info">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="image" id="imgUpload" hidden onchange="this.form.submit()"> <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control" value="<?= $p['name'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control" value="<?= $p['phone'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small mb-1">Email Address (Read Only)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control text-muted" value="<?= $p['email'] ?>" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small mb-1">Address</label>
                                <textarea name="address" class="form-control" rows="2"><?= $p['address'] ?></textarea>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" name="update_profile" class="btn btn-primary px-4 fw-bold shadow">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="pills-bank">
                    <div class="alert alert-info bg-opacity-10 border-0 text-info small mb-4">
                        <i class="fas fa-info-circle me-2"></i> These details will be used for withdrawing your earnings.
                    </div>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="text-muted small mb-1">National ID / Passport</label>
                                <input type="text" name="nid_number" class="form-control" value="<?= $p['nid_number'] ?>" placeholder="Enter ID Number">
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="<?= $p['bank_name'] ?>" placeholder="Ex: Al Rajhi Bank">
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Account Number / IBAN</label>
                                <input type="text" name="account_number" class="form-control font-monospace" value="<?= $p['account_number'] ?>" placeholder="XXXX-XXXX-XXXX">
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" name="update_bank" class="btn btn-primary px-4 fw-bold shadow">Update Bank Info</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="pills-sec">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Current Password</label>
                            <input type="password" name="old_pass" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">New Password</label>
                                <input type="password" name="new_pass" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Confirm Password</label>
                                <input type="password" name="confirm_pass" class="form-control" required>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" name="change_pass" class="btn btn-danger px-4 fw-bold shadow">Update Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
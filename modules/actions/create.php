<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$anomalies = $pdo->query("SELECT id, title FROM anomalies WHERE status='Open' ORDER BY title");
$users = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>New CAPA</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Create CAPA</h3></div>
<form method="POST" action="store.php">
<div class="card-body">
<div class="form-group mb-3">
<label>Anomaly</label>
<select name="anomaly_id" class="form-control" required>
<option value="">Select Anomaly</option>
<?php while($row = $anomalies->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Action Required</label>
<textarea name="action_required" rows="4" class="form-control" required></textarea>
</div>
<div class="form-group mb-3">
<label>Assigned To</label>
<select name="assigned_to" class="form-control">
<option value="">Select User</option>
<?php while($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['fullname']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Target Date</label>
<input type="date" name="target_date" class="form-control" required>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create CAPA</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
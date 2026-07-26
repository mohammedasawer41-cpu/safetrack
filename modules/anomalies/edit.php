<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'];

if(!$id) die("Invalid anomaly.");

$stmt = $pdo->prepare("SELECT a.*, u.fullname, i.inspection_no FROM anomalies a LEFT JOIN users u ON u.id=a.reported_by LEFT JOIN inspections i ON i.id=a.inspection_id WHERE a.id=?");
$stmt->execute([$id]);
$anomaly = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$anomaly) die("Anomaly not found.");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Anomaly</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Edit Anomaly</h3></div>
<form method="POST" action="update.php">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="card-body">
<div class="form-group mb-3">
<label>Title</label>
<input type="text" name="title" class="form-control" value="<?= htmlspecialchars($anomaly['title']) ?>" required>
</div>
<div class="form-group mb-3">
<label>Severity</label>
<select name="severity" class="form-control" required>
<option value="Low" <?= $anomaly['severity'] == 'Low' ? 'selected' : '' ?>>Low</option>
<option value="Medium" <?= $anomaly['severity'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
<option value="High" <?= $anomaly['severity'] == 'High' ? 'selected' : '' ?>>High</option>
<option value="Critical" <?= $anomaly['severity'] == 'Critical' ? 'selected' : '' ?>>Critical</option>
</select>
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Open" <?= $anomaly['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
<option value="Assigned" <?= $anomaly['status'] == 'Assigned' ? 'selected' : '' ?>>Assigned</option>
<option value="Closed" <?= $anomaly['status'] == 'Closed' ? 'selected' : '' ?>>Closed</option>
</select>
</div>
<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($anomaly['description'] ?? '') ?></textarea>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
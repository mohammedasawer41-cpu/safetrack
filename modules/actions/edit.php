<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'];
if(!$id) die("Invalid CAPA.");

$stmt = $pdo->prepare("SELECT ca.*, a.title FROM corrective_actions ca JOIN anomalies a ON a.id=ca.anomaly_id WHERE ca.id=?");
$stmt->execute([$id]);
$capa = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$capa) die("CAPA not found.");

$users = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit CAPA</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Edit CAPA</h3></div>
<form method="POST" action="update.php">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="card-body">
<div class="form-group mb-3">
<label>Anomaly</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($capa['title']) ?>" disabled>
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Open" <?= $capa['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
<option value="In Progress" <?= $capa['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
<option value="Completed" <?= $capa['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
</select>
</div>
<div class="form-group mb-3">
<label>Completion Date</label>
<input type="date" name="completion_date" class="form-control" value="<?= $capa['completion_date'] ?? '' ?>">
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
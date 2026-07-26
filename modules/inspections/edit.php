<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'] ?? 0;
if(!$id) die("Invalid inspection.");

$stmt = $pdo->prepare("SELECT i.*, u.fullname, s.site_name, a.area_name FROM inspections i LEFT JOIN users u ON u.id=i.inspector LEFT JOIN sites s ON s.id=i.site_id LEFT JOIN areas a ON a.id=i.area_id WHERE i.id=?");
$stmt->execute([$id]);
$inspection = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$inspection) die("Inspection not found.");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Inspection</h1></div>
<div class="col-sm-6 text-end"><a href="view.php?id=<?= $id ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Edit Inspection</h3></div>
<form method="POST" action="update.php">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="card-body">
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="Draft" <?= $inspection['status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
<option value="Completed" <?= $inspection['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
</select>
</div>
<div class="form-group mb-3">
<label>Remarks</label>
<textarea name="remarks" rows="4" class="form-control"><?= htmlspecialchars($inspection['remarks'] ?? '') ?></textarea>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
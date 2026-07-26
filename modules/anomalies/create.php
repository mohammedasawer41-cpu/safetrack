<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$inspections = $pdo->query("SELECT id, inspection_no FROM inspections ORDER BY inspection_no DESC");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>New Anomaly</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Create New Anomaly</h3></div>
<form method="POST" action="store.php">
<div class="card-body">
<div class="row">
<div class="col-md-6">
<div class="form-group mb-3">
<label>Inspection</label>
<select name="inspection_id" class="form-control">
<option value="">Select Inspection</option>
<?php while($row = $inspections->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['inspection_no']) ?></option>
<?php endwhile; ?>
</select>
</div>
</div>
<div class="col-md-6">
<div class="form-group mb-3">
<label>Severity</label>
<select name="severity" class="form-control" required>
<option value="Low">Low</option>
<option value="Medium" selected>Medium</option>
<option value="High">High</option>
<option value="Critical">Critical</option>
</select>
</div>
</div>
</div>
<div class="form-group mb-3">
<label>Title</label>
<input type="text" name="title" class="form-control" required>
</div>
<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" rows="4" class="form-control"></textarea>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Anomaly</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
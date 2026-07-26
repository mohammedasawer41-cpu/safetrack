<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'];

if(!$id) die("Invalid planning.");

$stmt = $pdo->prepare("SELECT * FROM inspection_schedule WHERE id=?");
$stmt->execute([$id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$schedule) die("Planning not found.");

$inspectors = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname");
$templates = $pdo->query("SELECT id, template_name FROM checklist_templates ORDER BY template_name");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Planning</h1></div>
<div class="col-sm-6 text-end"><a href="monthly.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Edit Planning</h3></div>
<form action="update_schedule.php" method="post">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="card-body">
<div class="form-group mb-3">
<label>Inspection Date</label>
<input type="date" name="inspection_date" class="form-control" value="<?= $schedule['inspection_date'] ?>" required>
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="Planned" <?= $schedule['status'] == 'Planned' ? 'selected' : '' ?>>Planned</option>
<option value="Assigned" <?= $schedule['status'] == 'Assigned' ? 'selected' : '' ?>>Assigned</option>
<option value="In Progress" <?= $schedule['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
<option value="Completed" <?= $schedule['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
</select>
</div>
<div class="form-group mb-3">
<label>Remarks</label>
<textarea name="remarks" rows="4" class="form-control"><?= htmlspecialchars($schedule['remarks'] ?? '') ?></textarea>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="monthly.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
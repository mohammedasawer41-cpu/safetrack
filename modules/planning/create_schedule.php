<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$inspectors = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname");
$templates = $pdo->query("SELECT id, template_name FROM checklist_templates ORDER BY template_name");
$sites = $pdo->query("SELECT id, site_name FROM sites ORDER BY site_name");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>New Inspection Planning</h1></div>
<div class="col-sm-6 text-end"><a href="monthly.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Inspection Planning Form</h3></div>
<form action="save_schedule.php" method="post">
<div class="card-body">
<div class="row">
<div class="col-md-4">
<div class="form-group">
<label>Inspection Date</label>
<input type="date" name="inspection_date" class="form-control" required>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Inspector</label>
<select name="inspector_id" class="form-control" required>
<option value="">Select Inspector</option>
<?php while($i=$inspectors->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['fullname']) ?></option>
<?php endwhile; ?>
</select>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Status</label>
<select name="status" class="form-control">
<option value="Planned">Planned</option>
<option value="Assigned">Assigned</option>
<option value="In Progress">In Progress</option>
<option value="Completed">Completed</option>
</select>
</div>
</div>
</div>
<div class="row mt-3">
<div class="col-md-4">
<div class="form-group">
<label>Site</label>
<select name="site_id" class="form-control">
<option value="">Select Site</option>
<?php while($s=$sites->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['site_name']) ?></option>
<?php endwhile; ?>
</select>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Checklist Template</label>
<select name="template_id" class="form-control" required>
<option value="">Select Checklist</option>
<?php while($t=$templates->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['template_name']) ?></option>
<?php endwhile; ?>
</select>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Location / Area</label>
<input type="text" name="location" class="form-control" placeholder="Warehouse, Production, Office..." required>
</div>
</div>
</div>
<div class="row mt-3">
<div class="col-md-12">
<div class="form-group">
<label>Remarks</label>
<textarea name="remarks" rows="4" class="form-control" placeholder="Additional notes..."></textarea>
</div>
</div>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Planning</button>
<a href="monthly.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
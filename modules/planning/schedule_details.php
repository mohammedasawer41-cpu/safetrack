<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid schedule.');

$stmt = $pdo->prepare("
    SELECT s.*, u.fullname as inspector, t.template_name, si.site_name, a.area_name
    FROM inspection_schedule s
    LEFT JOIN users u ON u.id=s.inspector_id
    LEFT JOIN checklist_templates t ON t.id=s.template_id
    LEFT JOIN sites si ON si.id=s.site_id
    LEFT JOIN areas a ON a.id=s.area_id
    WHERE s.id=?
");
$stmt->execute([$id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$schedule) die('Schedule not found.');

// Get linked anomalies (if inspection was created from this schedule)
$insp_stmt = $pdo->prepare("SELECT id FROM inspections WHERE schedule_id=? LIMIT 1");
$insp_stmt->execute([$id]);
$inspection_id = $insp_stmt->fetchColumn();

$anomalies = [];
if ($inspection_id) {
    $anom_stmt = $pdo->prepare("
        SELECT a.*, u.fullname as reported_by_name
        FROM anomalies a
        LEFT JOIN users u ON u.id=a.reported_by
        WHERE a.inspection_id=?
        ORDER BY a.reported_date DESC
    ");
    $anom_stmt->execute([$inspection_id]);
    $anomalies = $anom_stmt->fetchAll(PDO::FETCH_ASSOC);
}

$severity_colors = [
    'Low' => 'success',
    'Medium' => 'warning',
    'High' => 'danger',
    'Critical' => 'dark'
];

$status_colors = [
    'Planned' => 'info',
    'Assigned' => 'primary',
    'In Progress' => 'warning',
    'Completed' => 'success',
    'Verified' => 'success',
    'Closed' => 'secondary',
    'Cancelled' => 'danger'
];
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Inspection Schedule Details</h1></div>
<div class="col-sm-6 text-end"><a href="monthly.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<!-- Schedule Information -->
<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Schedule Information</h3></div>
<div class="card-body">
<div class="row">
<div class="col-md-6">
<table class="table table-borderless">
<tr><th width="150">Date</th><td><?= date('d/m/Y (l)', strtotime($schedule['inspection_date'])) ?></td></tr>
<tr><th>Inspector</th><td><?= htmlspecialchars($schedule['inspector'] ?? '-') ?></td></tr>
<tr><th>Checklist</th><td><?= htmlspecialchars($schedule['template_name'] ?? '-') ?></td></tr>
<tr><th>Site</th><td><?= htmlspecialchars($schedule['site_name'] ?? '-') ?></td></tr>
</table>
</div>
<div class="col-md-6">
<table class="table table-borderless">
<tr><th width="150">Location</th><td><?= htmlspecialchars($schedule['location'] ?? '-') ?></td></tr>
<tr><th>Status</th><td><span class="badge bg-<?= $status_colors[$schedule['status']] ?? 'secondary' ?> fs-6"><?= htmlspecialchars($schedule['status']) ?></span></td></tr>
<tr><th>Created</th><td><?= date('d/m/Y H:i', strtotime($schedule['created_at'])) ?></td></tr>
<tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($schedule['remarks'] ?? '-')) ?></td></tr>
</table>
</div>
</div>
</div>
<div class="card-footer">
<div class="btn-group" role="group">
<a href="edit_schedule.php?id=<?= $schedule['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal"><i class="fas fa-refresh"></i> Update Status</button>
<a href="delete_schedule.php?id=<?= $schedule['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this schedule?')"><i class="fas fa-trash"></i> Delete</a>
</div>
</div>
</div>

<!-- Anomalies Section -->
<div class="card card-danger mt-3">
<div class="card-header">
<h3 class="card-title">
<i class="fas fa-exclamation-triangle"></i> Linked Anomalies
<?php if (count($anomalies) > 0): ?>
<span class="badge bg-danger"><?= count($anomalies) ?></span>
<?php endif; ?>
</h3>
</div>
<div class="card-body">
<?php if (count($anomalies) > 0): ?>
<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr>
<th>Anomaly No</th>
<th>Title</th>
<th>Severity</th>
<th>Status</th>
<th>Reported By</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($anomalies as $anom): ?>
<tr>
<td><code><?= htmlspecialchars($anom['anomaly_no']) ?></code></td>
<td><?= htmlspecialchars($anom['title']) ?></td>
<td><span class="badge bg-<?= $severity_colors[$anom['severity']] ?? 'secondary' ?>"><?= htmlspecialchars($anom['severity']) ?></span></td>
<td>
<?php 
$anom_status_color = ['Open' => 'danger', 'Assigned' => 'warning', 'Closed' => 'success'];
?>
<span class="badge bg-<?= $anom_status_color[$anom['status']] ?? 'secondary' ?>"><?= htmlspecialchars($anom['status']) ?></span>
</td>
<td><?= htmlspecialchars($anom['reported_by_name'] ?? '-') ?></td>
<td><?= date('d/m/Y', strtotime($anom['reported_date'])) ?></td>
<td>
<a href="../../anomalies/view.php?id=<?= $anom['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php else: ?>
<div class="alert alert-info" role="alert">
<i class="fas fa-info-circle"></i> No anomalies recorded for this inspection.
</div>
<?php endif; ?>
</div>
</div>

</div>
</section>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Update Inspection Status</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form method="POST" action="quick_status.php">
<div class="modal-body">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Planned" <?= $schedule['status'] == 'Planned' ? 'selected' : '' ?>>Planned</option>
<option value="Assigned" <?= $schedule['status'] == 'Assigned' ? 'selected' : '' ?>>Assigned</option>
<option value="In Progress" <?= $schedule['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
<option value="Completed" <?= $schedule['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
<option value="Verified" <?= $schedule['status'] == 'Verified' ? 'selected' : '' ?>>Verified</option>
</select>
</div>
<div class="form-group mb-3">
<label>Notes</label>
<textarea name="remarks" rows="3" class="form-control"><?= htmlspecialchars($schedule['remarks'] ?? '') ?></textarea>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-primary">Update Status</button>
</div>
</form>
</div>
</div>
</div>

<?php include '../../includes/footer.php'; ?>
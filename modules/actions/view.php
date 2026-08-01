<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid CAPA action.');

$stmt = $pdo->prepare("SELECT ca.*, a.title, a.description, u.fullname FROM corrective_actions ca JOIN anomalies a ON a.id=ca.anomaly_id LEFT JOIN users u ON u.id=ca.assigned_to WHERE ca.id=?");
$stmt->execute([$id]);
$action = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$action) die('Action not found.');

$status_colors = ['Open' => 'danger', 'In Progress' => 'warning', 'Completed' => 'success'];
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>CAPA Action Details</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<div class="card">
<div class="card-header"><h3 class="card-title">Anomaly: <?= htmlspecialchars($action['title']) ?></h3></div>
<div class="card-body">
<table class="table table-borderless">
<tr><th width="200">Description</th><td><?= nl2br(htmlspecialchars($action['description'])) ?></td></tr>
<tr><th>Action Required</th><td><?= nl2br(htmlspecialchars($action['action_required'])) ?></td></tr>
<tr><th>Assigned To</th><td><?= htmlspecialchars($action['fullname'] ?? '-') ?></td></tr>
<tr><th>Target Date</th><td><?= $action['target_date'] ? date('d/m/Y', strtotime($action['target_date'])) : '-' ?></td></tr>
<tr><th>Completion Date</th><td><?= $action['completion_date'] ? date('d/m/Y', strtotime($action['completion_date'])) : '-' ?></td></tr>
<tr><th>Status</th><td><span class="badge bg-<?= $status_colors[$action['status']] ?>"><?= htmlspecialchars($action['status']) ?></span></td></tr>
</table>
</div>
<div class="card-footer">
<a href="edit.php?id=<?= $action['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
<a href="delete.php?id=<?= $action['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this action?')"><i class="fas fa-trash"></i> Delete</a>
</div>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
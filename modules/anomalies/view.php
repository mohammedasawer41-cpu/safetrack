<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid anomaly.');

$stmt = $pdo->prepare("SELECT a.*, u.fullname FROM anomalies a LEFT JOIN users u ON u.id=a.reported_by WHERE a.id=?");
$stmt->execute([$id]);
$anomaly = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anomaly) die('Anomaly not found.');

$severity_colors = ['Low' => 'success', 'Medium' => 'warning', 'High' => 'danger', 'Critical' => 'dark'];
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Anomaly Details</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<div class="card">
<div class="card-header"><h3 class="card-title">Anomaly: <?= htmlspecialchars($anomaly['anomaly_no']) ?></h3></div>
<div class="card-body">
<table class="table table-borderless">
<tr><th width="200">Title</th><td><?= htmlspecialchars($anomaly['title']) ?></td></tr>
<tr><th>Description</th><td><?= nl2br(htmlspecialchars($anomaly['description'])) ?></td></tr>
<tr><th>Severity</th><td><span class="badge bg-<?= $severity_colors[$anomaly['severity']] ?>"><?= htmlspecialchars($anomaly['severity']) ?></span></td></tr>
<tr><th>Status</th><td>
<?php $status_color = ['Open' => 'danger', 'Assigned' => 'warning', 'Closed' => 'success']; ?>
<span class="badge bg-<?= $status_color[$anomaly['status']] ?>"><?= htmlspecialchars($anomaly['status']) ?></span>
</td></tr>
<tr><th>Reported By</th><td><?= htmlspecialchars($anomaly['fullname'] ?? '-') ?></td></tr>
<tr><th>Reported Date</th><td><?= $anomaly['reported_date'] ? date('d/m/Y', strtotime($anomaly['reported_date'])) : '-' ?></td></tr>
</table>
</div>
<div class="card-footer">
<a href="edit.php?id=<?= $anomaly['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
<a href="delete.php?id=<?= $anomaly['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this anomaly?')"><i class="fas fa-trash"></i> Delete</a>
</div>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
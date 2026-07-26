<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'] ?? 0;
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
<div class="col-sm-6"><h1>Anomaly Details</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Information</h3></div>
<div class="card-body">
<table class="table table-bordered">
<tr><th width="200">Anomaly No</th><td><?= htmlspecialchars($anomaly['anomaly_no'] ?? '-') ?></td></tr>
<tr><th>Title</th><td><?= htmlspecialchars($anomaly['title']) ?></td></tr>
<tr><th>Description</th><td><?= nl2br(htmlspecialchars($anomaly['description'] ?? '-')) ?></td></tr>
<tr><th>Severity</th><td><span class="badge bg-danger"><?= htmlspecialchars($anomaly['severity']) ?></span></td></tr>
<tr><th>Status</th><td><span class="badge bg-warning"><?= htmlspecialchars($anomaly['status']) ?></span></td></tr>
<tr><th>Reported By</th><td><?= htmlspecialchars($anomaly['fullname'] ?? '-') ?></td></tr>
<tr><th>Reported Date</th><td><?= htmlspecialchars($anomaly['reported_date']) ?></td></tr>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
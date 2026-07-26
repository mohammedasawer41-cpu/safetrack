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
<div class="col-sm-6"><h1>Inspection Details</h1></div>
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
<tr><th width="200">Inspection No</th><td><?= htmlspecialchars($inspection['inspection_no']) ?></td></tr>
<tr><th>Date</th><td><?= htmlspecialchars($inspection['inspection_date']) ?></td></tr>
<tr><th>Inspector</th><td><?= htmlspecialchars($inspection['fullname'] ?? '-') ?></td></tr>
<tr><th>Site</th><td><?= htmlspecialchars($inspection['site_name'] ?? '-') ?></td></tr>
<tr><th>Area</th><td><?= htmlspecialchars($inspection['area_name'] ?? '-') ?></td></tr>
<tr><th>Status</th><td><span class="badge bg-<?= $inspection['status'] == 'Completed' ? 'success' : 'warning' ?>"><?= htmlspecialchars($inspection['status']) ?></span></td></tr>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
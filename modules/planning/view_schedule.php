<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'];

if(!$id) die("Invalid planning.");

$stmt = $pdo->prepare("SELECT s.*, u.fullname, c.template_name FROM inspection_schedule s LEFT JOIN users u ON u.id=s.inspector_id LEFT JOIN checklist_templates c ON c.id=s.template_id WHERE s.id=?");
$stmt->execute([$id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$schedule) die("Planning not found.");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row">
<div class="col-sm-6"><h1>Inspection Planning Details</h1></div>
<div class="col-sm-6 text-end"><a href="monthly.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Planning Information</h3></div>
<div class="card-body">
<table class="table table-bordered">
<tr><th width="220">Inspection Date</th><td><?= date("d/m/Y",strtotime($schedule['inspection_date'])) ?></td></tr>
<tr><th>Inspector</th><td><?= htmlspecialchars($schedule['fullname'] ?? '-') ?></td></tr>
<tr><th>Checklist</th><td><?= htmlspecialchars($schedule['template_name'] ?? '-') ?></td></tr>
<tr><th>Location</th><td><?= htmlspecialchars($schedule['location'] ?? '-') ?></td></tr>
<tr><th>Status</th><td><?= htmlspecialchars($schedule['status']) ?></td></tr>
<tr><th>Remarks</th><td><?= nl2br(htmlspecialchars($schedule['remarks'] ?? '-')) ?></td></tr>
</table>
</div>
<div class="card-footer">
<a href="start_inspection.php?schedule=<?= $schedule['id'] ?>" class="btn btn-success"><i class="fas fa-play"></i> Start Inspection</a>
<a href="edit_schedule.php?id=<?= $schedule['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
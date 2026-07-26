<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$total_inspections = $pdo->query("SELECT COUNT(*) FROM inspections")->fetchColumn();
$completed_inspections = $pdo->query("SELECT COUNT(*) FROM inspections WHERE status='Completed'")->fetchColumn();
$open_anomalies = $pdo->query("SELECT COUNT(*) FROM anomalies WHERE status='Open'")->fetchColumn();
$closed_anomalies = $pdo->query("SELECT COUNT(*) FROM anomalies WHERE status='Closed'")->fetchColumn();
$open_capa = $pdo->query("SELECT COUNT(*) FROM corrective_actions WHERE status='Open'")->fetchColumn();
$completed_capa = $pdo->query("SELECT COUNT(*) FROM corrective_actions WHERE status='Completed'")->fetchColumn();
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-chart-bar"></i> Reports</h1></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-lg-3 col-6"><div class="small-box bg-info"><div class="inner"><h3><?= $total_inspections ?></h3><p>Total Inspections</p></div><div class="icon"><i class="fas fa-clipboard-check"></i></div></div></div>
<div class="col-lg-3 col-6"><div class="small-box bg-success"><div class="inner"><h3><?= $completed_inspections ?></h3><p>Completed</p></div><div class="icon"><i class="fas fa-check-circle"></i></div></div></div>
<div class="col-lg-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3><?= $open_anomalies ?></h3><p>Open Anomalies</p></div><div class="icon"><i class="fas fa-exclamation-triangle"></i></div></div></div>
<div class="col-lg-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3><?= $open_capa ?></h3><p>Open CAPA</p></div><div class="icon"><i class="fas fa-tasks"></i></div></div></div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
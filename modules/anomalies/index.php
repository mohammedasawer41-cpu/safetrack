<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$status = $_GET['status'] ?? '';
$sql = "SELECT a.*, u.fullname FROM anomalies a LEFT JOIN users u ON u.id=a.reported_by";
if($status) $sql .= " WHERE a.status='" . htmlspecialchars($status) . "'";
$sql .= " ORDER BY a.reported_date DESC";

$stmt = $pdo->query($sql);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-exclamation-triangle"></i> Anomalies</h1></div>
<div class="col-sm-6 text-end"><a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Anomaly</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="row mb-3"><div class="col-12">
<div class="btn-group" role="group">
<a href="index.php" class="btn btn-<?= !$status ? 'primary' : 'secondary' ?>">All</a>
<a href="?status=Open" class="btn btn-<?= $status == 'Open' ? 'primary' : 'secondary' ?>">Open</a>
<a href="?status=Assigned" class="btn btn-<?= $status == 'Assigned' ? 'primary' : 'secondary' ?>">Assigned</a>
<a href="?status=Closed" class="btn btn-<?= $status == 'Closed' ? 'primary' : 'secondary' ?>">Closed</a>
</div>
</div>
</div>
<div class="card">
<div class="card-header"><h3 class="card-title">All Anomalies</h3></div>
<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr><th>No</th><th>Title</th><th>Severity</th><th>Status</th><th>Reported By</th><th>Date</th><th>Actions</th></tr>
</thead>
<tbody>
<?php if($stmt->rowCount() > 0): ?>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<?php $severity_color = ['Low' => 'success', 'Medium' => 'warning', 'High' => 'danger', 'Critical' => 'dark']; $status_color = ['Open' => 'danger', 'Assigned' => 'warning', 'Closed' => 'success']; ?>
<tr>
<td><?= htmlspecialchars($row['anomaly_no'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><span class="badge bg-<?= $severity_color[$row['severity']] ?? 'secondary' ?>"><?= htmlspecialchars($row['severity']) ?></span></td>
<td><span class="badge bg-<?= $status_color[$row['status']] ?? 'secondary' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
<td><?= htmlspecialchars($row['fullname'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['reported_date']) ?></td>
<td><a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="7" class="text-center">No anomalies found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$status = $_GET['status'] ?? '';
$sql = "SELECT ca.*, a.title, u.fullname FROM corrective_actions ca JOIN anomalies a ON a.id=ca.anomaly_id LEFT JOIN users u ON u.id=ca.assigned_to";
if($status) $sql .= " WHERE ca.status='" . htmlspecialchars($status) . "'";
$sql .= " ORDER BY ca.target_date ASC";

$stmt = $pdo->query($sql);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-tasks"></i> CAPA</h1></div>
<div class="col-sm-6 text-end"><a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Action</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="row mb-3"><div class="col-12">
<div class="btn-group" role="group">
<a href="index.php" class="btn btn-<?= !$status ? 'primary' : 'secondary' ?>">All</a>
<a href="?status=Open" class="btn btn-<?= $status == 'Open' ? 'primary' : 'secondary' ?>">Open</a>
<a href="?status=In+Progress" class="btn btn-<?= $status == 'In Progress' ? 'primary' : 'secondary' ?>">In Progress</a>
<a href="?status=Completed" class="btn btn-<?= $status == 'Completed' ? 'primary' : 'secondary' ?>">Completed</a>
</div>
</div>
</div>
<div class="card">
<div class="card-header"><h3 class="card-title">CAPA List</h3></div>
<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr><th>Anomaly</th><th>Action Required</th><th>Assigned To</th><th>Target Date</th><th>Status</th><th>Actions</th></tr>
</thead>
<tbody>
<?php if($stmt->rowCount() > 0): ?>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<?php $status_color = ['Open' => 'danger', 'In Progress' => 'warning', 'Completed' => 'success']; ?>
<tr>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars(substr($row['action_required'] ?? '', 0, 50)) ?>...</td>
<td><?= htmlspecialchars($row['fullname'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['target_date']) ?></td>
<td><span class="badge bg-<?= $status_color[$row['status']] ?? 'secondary' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
<td><a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6" class="text-center">No CAPA records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
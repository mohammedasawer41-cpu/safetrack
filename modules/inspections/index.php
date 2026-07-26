<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$stmt = $pdo->query("
SELECT i.*, u.fullname, s.site_name, a.area_name
FROM inspections i
LEFT JOIN users u ON u.id=i.inspector
LEFT JOIN sites s ON s.id=i.site_id
LEFT JOIN areas a ON a.id=i.area_id
ORDER BY i.inspection_date DESC
");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-clipboard-check"></i> Inspections</h1></div>
<div class="col-sm-6 text-end"><a href="../../modules/planning/monthly.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Inspection</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">All Inspections</h3></div>
<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr><th>Inspection No</th><th>Date</th><th>Inspector</th><th>Site</th><th>Area</th><th>Status</th><th>Actions</th></tr>
</thead>
<tbody>
<?php if($stmt->rowCount() > 0): ?>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
<td><?= htmlspecialchars($row['inspection_no'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['inspection_date'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['fullname'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['site_name'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['area_name'] ?? '-') ?></td>
<td><span class="badge bg-<?= $row['status'] == 'Completed' ? 'success' : 'warning' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
<td><a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="7" class="text-center">No inspections found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
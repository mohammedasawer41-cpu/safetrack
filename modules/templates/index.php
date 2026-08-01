<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$stmt = $pdo->query("
    SELECT ct.id, ct.template_name, ct.description, ct.created_at, 
           COUNT(cq.id) as question_count
    FROM checklist_templates ct
    LEFT JOIN checklist_questions cq ON cq.template_id = ct.id
    GROUP BY ct.id
    ORDER BY ct.template_name ASC
");
$templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-list-check"></i> Checklist Templates</h1></div>
<div class="col-sm-6 text-end"><a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> New Template</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">All Templates</h3></div>
<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr>
<th>Template Name</th>
<th>Description</th>
<th>Questions</th>
<th>Created</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if (count($templates) > 0): ?>
<?php foreach ($templates as $template): ?>
<tr>
<td><strong><?= htmlspecialchars($template['template_name']) ?></strong></td>
<td><?= htmlspecialchars(substr($template['description'] ?? '', 0, 60)) ?>...</td>
<td><span class="badge bg-info"><?= $template['question_count'] ?></span></td>
<td><?= date('d/m/Y', strtotime($template['created_at'])) ?></td>
<td>
<a href="view.php?id=<?= $template['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
<a href="edit.php?id=<?= $template['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
<a href="delete.php?id=<?= $template['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this template?')"><i class="fas fa-trash"></i></a>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="5" class="text-center">No templates found. <a href="create.php">Create one</a>.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>
